
![Code Coverage](https://raw.githubusercontent.com/ohyeahyao/phpunit-coverage-test/image-data/coverage.svg
)

Table of Contents
=================

## Database Quiz
- [Database Quiz 1](#database-quiz-1)
- [Database Quiz 2](#database-quiz-2)

## API Implements Quiz
- [Architecture Design](#architecture-design)
  * [Onion Architecture](#onion-architecture)
  * [OOP & Design Pattern](#oop--design-pattern)
    + [CheckAndTransformOrderController](#checkandtransformordercontroller)
    + [CurrencyConverter](#currencyconverter)
    + [CurrencyStrategyFactory](#currencystrategyfactory)
    + [OrderFormatValidator](#orderformatvalidator)
- [Notes](#notes)
  * [Directories](#directories)
  * [Continuous Integration (GitHub Action)](#continuous-integration-github-action)
- [References](#references)


## Database Quiz 1
### 請寫出一條查詢語句 (SQL)，列出在 2023 年 5 月下訂的訂單，使用台幣付款且5月總金額最多的前 10 筆的旅宿 ID (bnb_id), 旅宿名稱 (bnb_name), 5 月總金額 (may_amount)

#### Answer:
```SQL
SELECT 
    bnbs.id AS bnb_id,
    bnbs.name AS bnb_name,
    SUM(orders.amount) AS may_amount
FROM 
    orders
JOIN 
    bnbs ON orders.bnb_id = bnbs.id
WHERE 
    orders.currency = 'TWD'
    AND orders.created_at >= '2023-05-01'
    AND orders.created_at < '2023-06-01'
GROUP BY bnbs.id, bnbs.name
ORDER BY may_amount DESC
LIMIT 10;
```

## Database Quiz 2
### 在題目一的執行下，我們發現 SQL 執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優化的方式?

#### Answer:

針對**資料庫查詢**我會有幾個不同的角度進行優化的判斷: 
1. **SQL 本身分析**：EXPLAIN 該 SQL 掃描的行數與使用的索引，加入需要的索引，減少查詢的行數進而加快查詢速度。
2. **提高 RAM 的使用率和查詢效率**：檢查 DB 的 buffer_size 等設定。
3. **緩存機制**。若此資料是歷史且不會異動，則會開啟 DB 的快取功能，若該 DB 不支援則會將查詢的結果在 Application 層時針對查詢結果緩存，降低 DB 查詢壓力並加速查詢速度。
4. **分區表**：若 order 資料量過大，在可以不影響業務下，會考慮針對訂單日期規劃分區表，使用 PROCEDURE 和 EVENT 動態管理分區。
5. **優化硬體**：利用 Grafana 搭配 Prometheus 呈現監控可視化，判斷是否觀察是否達到硬體限制，可觀察 CPU, RAM usage 和 Disk I/O，來決定是否要將 Disk 升級 SSD 或是增加運算資源。

--
# API Implements Quiz

## Architecture Design
### Onion Architecture

![Onion Architecture](https://github.com/ohyeahyao/asiayo-2024-interview/blob/main/docs/img-onion-architecture.png)

為了讓核心的 Domain Model 的 Business Logic 受到完整的保護。
優點：
1. 保持靈活性、移植性不依賴 Framework
2. Domain Model(這專案是 Order) 是獨立的，因此可快速測試系統核心邏輯
3. 內層核心不會呼叫外層的元件，而是由外層反轉注入內層核心


### OOP & Design Pattern
#### - CheckAndTransformOrderController
`App\Http\Controllers\CheckAndTransformOrderController`
- SRP: 處理訂單檢查和轉換的 HTTP 請求。不涉及其他責任（如：具體的貨幣轉換邏輯、訂單驗證邏輯）。

- OCP: 對擴展開放、修改封閉。可以通過注入不同的 CurrencyConverterInterface 實現來擴展貨幣轉換功能，而不需要修改 Controller 代碼。

- LSP: 依賴於 CurrencyConverterInterface，外部服務只需要依賴 Interface 即可。

- ISP: 只依賴於 CurrencyConverterInterface，該 Interface 只包含必要的方法，不會讓 Controller 依賴不需要的方法。

- DIP: 依賴於抽象的 CurrencyConverterInterface，而不是 concrete，實現解耦。

- Dependency Injection: CurrencyConverterInterface 通過 construct 注入到 CheckAndTransformOrderController，可以動態地使用 OrderServiceProvider 調整貨幣轉換的策略與驗證規則。


#### - CurrencyConverter
`Modules\Order\UseCases\CurrencyConverter`

- SRP: CurrencyConverter 的單一職責是處理貨幣轉換。它的所有方法和屬性都支持這一職責，而不涉及其他責任（如：驗證訂單邏輯、貨幣轉換策略產生）。

- OCP: CurrencyConverter 擴展開放（可以通過添加新的貨幣轉換策略進行擴展），對修改封閉（不需要修改現有代碼即可添加新功能）。
Example：使用 CurrencyStrategyFactory 來建立不同的策略，可以通過加入新的 Strategy 來擴展貨幣轉換功能，而不需要修改 CurrencyConverter 的代碼。

- LSP: CurrencyConverter implements CurrencyConverterInterface，因此外部服務只需要依賴 Interface 即可。

- ISP: CurrencyConverter implements CurrencyConverterInterface，這個 interface 只包含必要的方法，不會讓 implementing classes 依賴不需要的 method。

- DIP: CurrencyConverter 依賴於 Interface(CurrencyStrategyFactoryInterface, OrderFormatValidatorInterface)，而不是具體的實現。

#### - CurrencyStrategyFactory
`Modules\Order\ConversionStrategies\CurrencyStrategyFactory`
- 實作**抽象工廠模式**，負責產生貨幣轉換的物件，當加入新的貨幣轉換功能只需要修改此工廠即可。

#### - OrderFormatValidator
`Modules\Order\Validators\OrderFormatValidator`
- 實作**策略模式**，透過 $validationRules 動態調整 Validation Rules，每個 Rule 實現了 ValidationRule Interface。



# Notes

## Directories

```
.
├── app
│   ├── Constants
│   ├── Http
│   └── Providers
├── docker
│   └── php
├── html-coverage
├── modules
│   ├── Order
│   └── Shared
├── tests
│   ├── Feature
│   └── Unit
....
```

### app

包含 Application 的邏輯和組件，有可能是 HTTP、Command、Scheduler 等應用程式形態出現。

- **Constants**：用於存放常量定義，例如: Route Path
- **Http**：包含 Controller、FormRequest 等與 HTTP 請求處理相關的 Class
- **Providers**：Service Provider，用於註冊服務和依賴注入，例如: OrderServiceProvider

### docker

用於存放 Docker 配置文件和相關資源

- **php**：包含 PHP8.3 相關的 Dockerfile

### html-coverage

```
$ ./vendor/bin/phpunit
```
Code Coverage Report，通常由 phpunit 工具生成 `./html-coverage`

![Code Coverage](https://github.com/ohyeahyao/asiayo-2024-interview/blob/main/docs/img-code-coverage.png)
![Code Coverage](https://github.com/ohyeahyao/asiayo-2024-interview/blob/main/docs/img-code-coverage-text.png)


### modules

用於組織模組化與業務邏輯的專案結構，每個子目錄代表一個獨立的功能模組

- **Order**：與訂單處理相關的模組
- **Shared**：包含多個模組之間共享的程式碼和資源

### tests

包含測試程式碼

- **Feature**：功能測試，通常用於測試應用程式的完整行為

  `./tests/Feature/CheckAndTransformOrderControllerTest.php` 包含題目需求的功能測試：

  - **name**
    - 包含非英文字母，丟出 400 - Name contains non-English characters
    - 每個單詞首字母非大寫，丟出 400 - Name is not capitalized
  - **price**
    - 訂單金額超過 2000，丟出 400 - Price is over 2000
  - **currency**
    - 貨幣格式若非 TWD 或 USD，丟出 400 - Currency format is wrong
    - 當貨幣為 USD 時，需修改 price 金額乘上固定匯率 31 元，並且將 currency 改為 TWD

- **Unit**：單元測試

## Continuous Integration (Github Action)
本專案實作了持續集成（CI）以確保代碼質量，並自動生成代碼覆蓋率報告。使用 [GitHub Actions](https://github.com/features/actions) 進行 CI，並使用 [Codacy](https://www.codacy.com/) 進行代碼覆蓋率報告。


# References

- http://epic.tesio.it/doc/manual/the_bellis_perennis.html
- https://www.kenming.idv.tw/microservices-internal-structure_onion-architecture/