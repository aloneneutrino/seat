# seat

图书馆座位预约系统

# 登录


图书馆座位预约系统的API使用了自签发的证书和新型加密套件

使用fiddler抓包

首先 get `https://172.26.50.21:8443/rest/auth?username=学号&password=密码`

如果密码不对，返回

```
{
  "status": "fail",
  "code": "13",
  "message": "登录失败: 用户名或密码不正确",
  "data": null
}
```

登录成功的话返回

```
{
  "status": "success",
  "data": {
    "token": "I6C6NJF8WV09270248"
  },
  "code": "0",
  "message": ""
}
```

token每次都会不同

# 用户信息

接下来的请求都要在header里带上token

```
GET https://172.26.50.21:8443/rest/v2/user HTTP/1.1
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
token: NG2ZK699P709295355
Host: 172.26.50.21:8443
Connection: Keep-Alive
```
一般返回是这样的
```
{
  "status": "success",
  "data": {
    "id": 2210,
    "enabled": true,
    "name": "姓名",
    "username": "学号",
    "username2": null,
    "status": "NORMAL",
    "lastLogin": "2018-09-16T16:44:23.000",
    "checkedIn": false,
    "lastIn": null,
    "lastOut": null,
    "lastInBuildingId": null,
    "lastInBuildingName": null,
    "violationCount": 0
  },
  "message": "",
  "code": "0"
}
```

# 设置

`/rest/v2/settings`

```
{
  "data": {
    "expressCompleteMins": 0,
    "buildingExpressCheckoutStatuses": [
      [
        1,
        false
      ]
    ],
    "buildingOpenClose": [
      [
        1,
        "07:00",
        "22:00"
      ]
    ],
    "wifiAwayDisabled": false,
    "wifiStopDisabled": false,
    "checkInAheadMins": 30,
    "lateAllowedMins": 15
  },
  "message": null,
  "status": true
}
```

checkInAheadMins 允许提前多少分钟签到

lateAllowedMins 迟到多少分钟后算违约

# 违约查询

`/rest/v2/violations`

没有违约的话返回
```
{
  "data": [],
  "message": null,
  "status": true
}

```

# 预约查询

`/rest/v2/user/reservations`

```
{
  "status": "success",
  "data": [
    {
      "id": 14757,
      "receipt": "0211-757-7",
      "onDate": "2018-09-30",
      "seatId": 13128,
      "status": "RESERVE",
      "location": "图书馆4层4F北区，座位号450",
      "begin": "10:00",
      "end": "12:00",
      "actualBegin": null,
      "awayBegin": null,
      "awayEnd": null,
      "userEnded": false,
      "message": "请在 09月30日09点30分 至 10点15分 之间前往场馆签到"
    }
  ],
  "message": "",
  "code": "0"
}
```

# 座位情况

`/rest/v2/room/layoutByDate/21/2018-09-29`

21大概是座位的区域，后面是日期

返回
```
{
  "status": "success",
  "message": "",
  "code": "0",
  "data": {
    "id": 21,
    "cols": 13,
    "rows": 31,
    "name": "2F北厅",
    "layout": {
      "2004": {
        "id": 8910,
        "name": "001",
        "type": "seat",
        "status": "FREE",
        "window": false,
        "power": false,
        "computer": false,
        "local": false
      }
    }
  }
}
```
layout里好多都只有一个`{"type":"empty"}`,不知道是什么情况

layout里name是真正的座位号， `{"type":"seat"}`是座位，type还可能是desk什么的

status FREE是没人可以预约，IN_USE是已经被占了

window 是不是靠窗

power 桌上有没有电源插座

# 按日期选座
