# 勤怠管理システム - Laravel

## 📌 概要
このアプリケーションは、一般ユーザーと管理者の両方の役割に対応した勤怠管理システムです。  
出勤・退勤・休憩・修正申請などの勤怠情報を記録・管理し、管理者が申請を承認する機能を備えています。

---

## 🔐 認証
Laravel Fortify を利用したユーザー認証（メール認証含む）を導入しています。

- 会員登録／ログイン：Fortify
- メール認証：必須
- 管理者判定：`can:admin` ミドルウェアによるアクセス制御

---

## 🧭 ルーティング概要

### 一般ユーザー
| 機能 | パス | コントローラ/アクション |
|------|------|--------------------------|
| 出勤画面表示 | `/attendance` | `AttendanceController@index` |
| 勤怠一覧 | `/attendances` | `AttendanceController@monthly` |
| 勤怠詳細 | `/attendances/{id}` | `AttendanceController@show` |
| 修正申請 | `/attendances/{id}/request-edit` | `AttendanceController@requestEdit` |
| 修正申請一覧 | `/my-requests` | `AttendanceController@requestList` |

### 管理者
| 機能 | パス | コントローラ/アクション |
|------|------|--------------------------|
| 日別勤怠一覧 | `/admin/attendances` | `Admin\AttendanceController@daily` |
| 勤怠詳細 | `/admin/attendances/{id}` | `Admin\AttendanceController@show` |
| ユーザー一覧 | `/admin/users` | `UserController@index` |
| 月次勤怠 | `/admin/users/{id}/monthly` | `UserController@showMonthlyAttendance` |
| 申請一覧 | `/admin/requests` | `RequestController@index` |
| 申請詳細 | `/admin/requests/{id}` | `RequestController@show` |
| 申請承認 | `/admin/requests/{id}/approve` | `RequestController@approve` |

---

## 🖼️ 画面構成（Bladeファイル）

- `resources/views/auth/`  
  - `login.blade.php`, `register.blade.php`
- `resources/views/attendance/`  
  - 出勤登録・一覧・詳細・申請画面など
- `resources/views/admin/`  
  - 管理者側の日別勤怠、ユーザー一覧、申請一覧など

---

## ✅ バリデーション

| ファイル | 対象フォーム | 主なルール |
|----------|--------------|------------|
| `RegisterRequest` | 会員登録 | name, email, password（必須・形式チェック） |
| `LoginRequest` | ログイン | email, password（必須） |
| `AttendanceEditRequest` | 勤怠修正申請 | 出退勤、休憩、備考の時刻・整合性チェック |

---

## 🧱 使用技術スタック

- Laravel 10.x
- PHP 8.2
- Docker / MySQL / Mailhog
- Laravel Fortify（認証機能）

---

## 🗂 モデル一覧

- `User`
- `Attendance`
- `AttendanceRequest`
- `BreakTime`

---

## 💬 補足
- 認証が必要なルートには `auth` ミドルウェア、管理者専用には `can:admin` を付与
- デザインは Bootstrapベース、またはカスタムCSSで調整
