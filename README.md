
```
$ ./vendor/bin/phpunit --no-coverage
```

### 需求
```
name
- 包含非英文字母，丟出 400 - Name contains non-English characters
- 每個單字首字母非大寫，丟出 400 - Name is not capitalized

price
- 訂單金額超過 2000，丟出 400 - Price is over 2000

currency
- 貨幣格式若非 TWD 或 USD，丟出 400 - Currency format is wrong
- 當貨幣為 USD 時，需修改 price 金額乘上固定匯率 31 元，並且將 currency 改為 TWD
```

## 建立
1. 非英文字母
```
php artisan make:rule ContainsNotEnglishCharactersRule
```

2. 每個單字字首非大寫

```
php artisan make:rule EachWordCapitalizedRule
```

3. 訂單金額超過 $x
```
php artisan make:rule AmountExceedsRule
```

4. 貨幣格式若非 TWD 或 USD
```
php artisan make:rule ValidCurrencyRule
```