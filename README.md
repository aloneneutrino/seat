# seat

图书馆座位预约系统

## 晚上十二点左右有一段时间是系统维护

这时 api 会返回

```
{"status":"fail","code":"10","message":"System Maintenance","data":null}
```

## 登录

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

## 用户信息

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

## 设置

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

## 违约查询

`/rest/v2/violations`

没有违约的话返回
```
{
  "data": [],
  "message": null,
  "status": true
}

```

## 预约查询

还没到时间，没有履约的预约 

`/rest/v2/user/reservations`

```
{"status":"success","data":null,"message":"","code":"0"}
```

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

## 预约历史记录

`/rest/v2/history/$page/$count`

```
{
  "status": "success",
  "data": {
    "reservations": [
      {
        "id": 14757,
        "date": "2018-9-30",
        "begin": "10:00",
        "end": "12:00",
        "awayBegin": null,
        "awayEnd": null,
        "loc": "图书馆4层4F北区450号",
        "stat": "MISS"
      },
      {
        "id": 3665,
        "date": "2018-9-16",
        "begin": "17:00",
        "end": "18:00",
        "awayBegin": null,
        "awayEnd": null,
        "loc": "图书馆3层3F北区089号",
        "stat": "COMPLETE"
      }
    ]
  },
  "message": "",
  "code": "0"
}
``` 

MISS 就是失约， COMPLETE 就是履约

## 图书馆信息

`/rest/v2/free/filters`

```
{
  "status": "success",
  "data": {
    "buildings": [
      [
        1,
        "图书馆",
        5
      ]
    ],
    "rooms": [
      [
        11,
        "3F北区",
        1,
        3
      ],
      [
        12,
        "3F南区",
        1,
        3
      ],
      [
        14,
        "5F西区",
        1,
        5
      ],
      [
        16,
        "4F西北区",
        1,
        4
      ],
      [
        17,
        "4F西南区",
        1,
        4
      ],
      [
        21,
        "2F北厅",
        1,
        2
      ],
      [
        22,
        "4F南1区",
        1,
        4
      ],
      [
        23,
        "4F南2区",
        1,
        4
      ],
      [
        24,
        "4F北区",
        1,
        4
      ]
    ],
    "hours": 10,
    "dates": [
      "2018-09-30",
      "2018-10-01"
    ]
  },
  "message": "",
  "code": "0"
}
```

"3F北区"之前的大概就是区域的编号

data->dates 是可以预约的日期

## 查每个区域有多少座位

`/rest/v2/room/stat2/1`

```
{
  "status": "success",
  "data": [
    {
      "roomId": 21,
      "room": "2F北厅",
      "floor": 2,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 56,
      "free": 56
    },
    {
      "roomId": 11,
      "room": "3F北区",
      "floor": 3,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 84,
      "free": 84
    },
    {
      "roomId": 12,
      "room": "3F南区",
      "floor": 3,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 84,
      "free": 84
    },
    {
      "roomId": 24,
      "room": "4F北区",
      "floor": 4,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 120,
      "free": 120
    },
    {
      "roomId": 22,
      "room": "4F南1区",
      "floor": 4,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 134,
      "free": 134
    },
    {
      "roomId": 23,
      "room": "4F南2区",
      "floor": 4,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 42,
      "free": 42
    },
    {
      "roomId": 16,
      "room": "4F西北区",
      "floor": 4,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 40,
      "free": 40
    },
    {
      "roomId": 17,
      "room": "4F西南区",
      "floor": 4,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 222,
      "free": 222
    },
    {
      "roomId": 14,
      "room": "5F西区",
      "floor": 5,
      "maxHour": -1,
      "reserved": 0,
      "inUse": 0,
      "away": 0,
      "totalSeats": 16,
      "free": 16
    }
  ],
  "message": "",
  "code": "0"
}
```

## 按区域和日期查每个座位的情况

`/rest/v2/room/layoutByDate/$room/$date`

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

layout里name是图书馆里贴在桌子上的座位号， `{"type":"seat"}`是座位，type还可能是desk,word什么的

status FREE是没人可以预约，IN_USE是已经被占了

window 是不是靠窗

power 桌上有没有电源插座

## 座位开始时间

`/rest/v2/startTimesForSeat/$id/$date`

$id 一般是四到五位，比如 8910 

```
{
  "status": "success",
  "data": {
    "startTimes": [
      {
        "id": "420",
        "value": "07:00"
      },
      {
        "id": "480",
        "value": "08:00"
      },
      {
        "id": "540",
        "value": "09:00"
      },
      {
        "id": "600",
        "value": "10:00"
      },
      {
        "id": "660",
        "value": "11:00"
      },
      {
        "id": "720",
        "value": "12:00"
      },
      {
        "id": "780",
        "value": "13:00"
      },
      {
        "id": "840",
        "value": "14:00"
      },
      {
        "id": "900",
        "value": "15:00"
      },
      {
        "id": "960",
        "value": "16:00"
      },
      {
        "id": "1020",
        "value": "17:00"
      },
      {
        "id": "1080",
        "value": "18:00"
      },
      {
        "id": "1140",
        "value": "19:00"
      },
      {
        "id": "1200",
        "value": "20:00"
      },
      {
        "id": "1260",
        "value": "21:00"
      }
    ]
  },
  "message": "",
  "code": "0"
}
```

注意这里的id是一天中的多少分钟

## 根据开始时间得到的座位结束时间

`/rest/v2/endTimesForSeat/$id/$date/$start`

$start 是开始时间

最多可以连续约8小时

```
{
  "status": "success",
  "data": {
    "endTimes": [
      {
        "id": "660",
        "value": "11:00"
      },
      {
        "id": "720",
        "value": "12:00"
      },
      {
        "id": "780",
        "value": "13:00"
      },
      {
        "id": "840",
        "value": "14:00"
      },
      {
        "id": "900",
        "value": "15:00"
      },
      {
        "id": "960",
        "value": "16:00"
      },
      {
        "id": "1020",
        "value": "17:00"
      },
      {
        "id": "1080",
        "value": "18:00"
      },
      {
        "id": "1140",
        "value": "19:00"
      },
      {
        "id": "1200",
        "value": "20:00"
      }
    ]
  },
  "message": "",
  "code": "0"
}
```

## 预约一个座位

`/rest/v2/freeBook`

```
POST "t=1&startTime=600&endTime=720&seat=13128&date=2018-09-30&t2=2"
```

可以看出来这是预约13128号座位，从9月30号上午10:00到12:00

正常的话，返回

```
{
  "status": "success",
  "data": {
    "id": 14757,
    "receipt": "0211-757-7",
    "onDate": "2018 年 09 月 30 日",
    "begin": "10 : 00",
    "end": "12 : 00",
    "location": "图书馆4层4F北区，座位号450",
    "checkedIn": false
  },
  "message": "",
  "code": "0"
}
```

## 取消预约

`/rest/v2/cancel/$id`

要取消上面的预约的话这里$id应该是14757
