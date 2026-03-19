# 🤖 Bot Registration Flow - Complete Guide

## Overview

The bot registration system is **fully implemented** and ready to use. When a Telegram user sends `/start` to the bot, they go through an interactive multi-step form to either register as an **Employer (Ish beruvchi)** or **Job Seeker (Ish qidiruvchi)**.

---

## Architecture

```
┌─────────────────────┐
│   Telegram User     │
│  @shimbayy_taksi_  │
│       _bot          │
└──────────┬──────────┘
           │
           │ /start, messages, callbacks
           │
┌──────────▼──────────────────────────────┐
│   Python Bot (aiogram 3.7+)             │
│   - FSM State Management                │
│   - Multi-step Forms                    │
│   - Keyboards (Callbacks)               │
└──────────┬──────────────────────────────┘
           │
           │ HTTP + X-BOT-TOKEN header
           │ POST /api/bot/users/upsert
           │ POST /api/bot/employers/upsert
           │ POST /api/bot/seekers/upsert
           │ GET /api/bot/regions
           │ GET /api/bot/seekers-types
           │ GET /api/bot/subjects
           │
┌──────────▼───────────────────────────────┐
│   Laravel API (PHP 8.2+)                 │
│   - Bot API Controllers                 │
│   - BotTokenMiddleware (validation)     │
│   - Database Persistence                │
└──────────┬──────────────────────────────┘
           │
           │ ORM (Eloquent)
           │
┌──────────▼──────────────────────────────┐
│   MySQL Database                        │
│   - Users                               │
│   - Seekers (Profiles)                  │
│   - Employers (Profiles)                │
│   - Regions, SeekersTypes, Subjects     │
└─────────────────────────────────────────┘
```

---

## Registration Flows

### Flow 1: Seeker (Ish qidiruvchi) Registration

```
1. /start
   └─> Upsert User (telegram_id, name, lastname, username)
   
2. Choose Role
   └─> Callback: role:seeker
   
3. Multi-step Form
   └─> Region (callback: seek_region:{id})
       └─> Experience (text input, optional)
           └─> Salary Min (integer, optional)
               └─> Work Format (callback: work_format:{format})
                   └─> About Me (text, optional)
                       └─> Seeker Type (callback: seek_type:{id})
                           └─> Subject (callback: seek_subject:{id} or skip)
                               └─> CV File (document upload or skip)
                                   └─> POST /api/bot/seekers/upsert
                                       └─> ✅ Profile Created!
```

### Flow 2: Employer (Ish beruvchi) Registration

```
1. /start
   └─> Upsert User (telegram_id, name, lastname, username)
   
2. Choose Role
   └─> Callback: role:employer
   
3. Multi-step Form
   └─> Organization Name (text, required)
       └─> Org Type (callback: org_type:{type})
           └─> Region (callback: emp_region:{id})
               └─> City (text, optional)
                   └─> District (text, optional)
                       └─> Address (text, optional)
                           └─> Contact Info (text, required)
                               └─> POST /api/bot/employers/upsert
                                   └─> ✅ Profile Created!
```

---

## FSM (Finite State Machine) States

### RegistrationRole Group
- `choosing` — User chooses between employer/seeker

### EmployerForm Group
- `org_name` — Organization name input
- `org_type` — Organization type selection
- `region` — Region selection
- `city` — City input
- `district` — District input
- `address` — Address input
- `org_contact` — Contact information

### SeekerForm Group
- `region` — Region selection
- `experience` — Experience description
- `salary_min` — Minimum salary
- `work_format` — Work format (online/offline/hybrid)
- `about_me` — About me description
- `seeker_type` — Seeker type (teacher/tutor/etc)
- `subject` — Subject selection
- `cv_file` — CV file upload

---

## API Endpoints

### User Management
| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/bot/users/upsert` | POST | Create/update user from bot |
| `/api/bot/users` | GET | List all users (paginated) |
| `/api/bot/users/{id}` | GET | Get specific user |
| `/api/bot/users/{id}` | PUT | Update user |
| `/api/bot/users/{id}` | DELETE | Delete user |

### Seeker Management
| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/bot/seekers/upsert` | POST | Create/update seeker profile |
| `/api/bot/seekers` | GET | List all seekers (paginated) |
| `/api/bot/seekers/{id}` | GET | Get specific seeker |
| `/api/bot/seekers/{id}` | PUT | Update seeker |
| `/api/bot/seekers/{id}` | DELETE | Delete seeker |

### Employer Management
| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/bot/employers/upsert` | POST | Create/update employer profile |
| `/api/bot/employers` | GET | List all employers (paginated) |
| `/api/bot/employers/{id}` | GET | Get specific employer |
| `/api/bot/employers/{id}` | PUT | Update employer |
| `/api/bot/employers/{id}` | DELETE | Delete employer |

### Lookup Data
| Endpoint | Method | Response | Purpose |
|----------|--------|----------|---------|
| `/api/bot/regions` | GET | Array of regions | Get available regions |
| `/api/bot/seekers-types` | GET | Array of seeker types | Get seeker type options |
| `/api/bot/subjects` | GET | Array of subjects | Get subject options |

---

## Request/Response Examples

### 1. Upsert User

**Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/bot/users/upsert \
  -H "X-BOT-TOKEN: nukus" \
  -H "Content-Type: application/json" \
  -d '{
    "telegram_id": 123456789,
    "name": "Abdulloh",
    "lastname": "Karimov",
    "username": "abdulloh123",
    "role": "seeker"
  }'
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Abdulloh",
    "lastname": "Karimov",
    "username": "abdulloh123",
    "telegram_id": 123456789,
    "role": {
      "id": 2,
      "name": "seeker"
    }
  }
}
```

### 2. Upsert Seeker

**Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/bot/seekers/upsert \
  -H "X-BOT-TOKEN: nukus" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "region_id": 1,
    "seeker_type_id": 1,
    "experience": "5 yillik tajriba",
    "salary_min": 5000000,
    "work_format": "online",
    "about_me": "O\'qituvchi va repetitor",
    "subject_id": 1
  }'
```

**Response:**
```json
{
  "seeker": {
    "id": 1,
    "user_id": 1,
    "region_id": 1,
    "seekertype_id": 1,
    "experience": "5 yillik tajriba",
    "salary_min": 5000000,
    "work_format": "online",
    "about_me": "O'qituvchi va repetitor",
    "subject_id": 1,
    "region": {
      "id": 1,
      "name": "Tashkent City",
      "slug": "tashkent"
    },
    "seekersType": {
      "id": 1,
      "name": "Teacher",
      "label": "O'qituvchi"
    }
  }
}
```

### 3. Upsert Employer

**Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/bot/employers/upsert \
  -H "X-BOT-TOKEN: nukus" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 2,
    "org_name": "Kimyo Maktabi",
    "org_type": "school",
    "region_id": 1,
    "city": "Tashkent",
    "district": "Yashnabad",
    "address": "Mustaqillik ko'chasi, 15",
    "org_contact": "+99887654321"
  }'
```

**Response:**
```json
{
  "employer": {
    "id": 1,
    "user_id": 2,
    "org_name": "Kimyo Maktabi",
    "org_type": "school",
    "region_id": 1,
    "city": "Tashkent",
    "district": "Yashnabad",
    "adress": "Mustaqillik ko'chasi, 15",
    "org_contact": "+99887654321",
    "is_verified": false,
    "is_active": true,
    "region": {
      "id": 1,
      "name": "Tashkent City",
      "slug": "tashkent"
    }
  }
}
```

### 4. Get Regions

**Request:**
```bash
curl http://127.0.0.1:8000/api/bot/regions \
  -H "X-BOT-TOKEN: nukus"
```

**Response:**
```json
{
  "regions": [
    {
      "id": 1,
      "name": "Tashkent City",
      "slug": "tashkent"
    },
    {
      "id": 2,
      "name": "Bukhara",
      "slug": "bukhara"
    }
  ]
}
```

---

## Key Features

### ✅ Smart Form Navigation
- **Back button** — Go to previous step
- **Cancel button** — Abort registration (clears state)
- **Skip tokens** — Skip optional fields using `-`, `skip`, `yo'q`, `otkazib yuborish`

### ✅ Error Handling
- **Validation errors** — User-friendly error messages in Uzbek
- **API errors** — Connection retry logic (up to 2 retries)
- **Field-level errors** — Specific guidance per field

### ✅ Multi-language Support
- **Uzbek UI** — All prompts and buttons in Uzbek
- **Error messages** — Localized validation messages
- **Field labels** — Translated for user clarity

### ✅ Database Constraints
- **Unique constraint** — One seeker/employer per user
- **Foreign key validation** — Region, Subject, SeekersType must exist
- **Transactional safety** — Prevents duplicate submissions

---

## Configuration

### Laravel .env
```bash
BOT_API_TOKEN=nukus                        # Auth token for bot API
BOT_API_BASE_URL=http://127.0.0.1:8000/api  # Laravel API URL
TELEGRAM_BOT_TOKEN=8054869730:AAHE...     # Telegram bot token
TELEGRAM_BOT_USERNAME=shimbayy_taksi_bot  # Bot username for display
```

### Python bot/.env
```bash
BOT_TOKEN=8054869730:AAHE...              # Telegram bot token (same as above)
API_BASE_URL=http://127.0.0.1:8000/api    # Laravel API URL
BOT_API_TOKEN=nukus                       # Auth token (same as Laravel)
BOT_TIMEOUT=30                            # Request timeout
LOG_LEVEL=DEBUG                           # Logging level
DOWNLOAD_DIR=./downloads                  # CV download directory
```

---

## How to Start

### 1. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 2. Start Laravel Development Server
```bash
php artisan serve
```
Server will be at `http://127.0.0.1:8000`

### 3. Start Python Bot
```bash
python bot/app.py
```
Bot will start polling for Telegram updates

### 4. Test Registration Flow
Open Telegram and search for `@shimbayy_taksi_bot`, then send `/start`

---

## Testing the API Manually

### Test User Upsert
```bash
curl -X POST http://127.0.0.1:8000/api/bot/users/upsert \
  -H "X-BOT-TOKEN: nukus" \
  -H "Content-Type: application/json" \
  -d '{
    "telegram_id": 999888777,
    "name": "Test",
    "lastname": "User",
    "username": "testuser999",
    "role": "seeker"
  }' | jq .
```

### Test Get Regions
```bash
curl http://127.0.0.1:8000/api/bot/regions \
  -H "X-BOT-TOKEN: nukus" | jq .
```

### Test Seeker Upsert
```bash
# First, note the user_id from the user upsert response
curl -X POST http://127.0.0.1:8000/api/bot/seekers/upsert \
  -H "X-BOT-TOKEN: nukus" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 999,
    "region_id": 1,
    "seeker_type_id": 1,
    "experience": "5 years",
    "salary_min": 5000000,
    "work_format": "online"
  }' | jq .
```

---

## Troubleshooting

### Bot not responding to /start
- Verify `TELEGRAM_BOT_TOKEN` is correct (test with `test_bot_token.py`)
- Check bot is running: `python bot/app.py`
- Check logs: `tail -f bot/logs/bot.log` (if configured)

### "Unauthorized" errors
- Verify `BOT_API_TOKEN` value in both `.env` and `bot/.env`
- Ensure header is exactly `X-BOT-TOKEN` (uppercase)
- Check middleware: `app/Http/Middleware/BotTokenMiddleware.php`

### "Region not found" errors
- Run migrations: `php artisan migrate`
- Seed data: `php artisan db:seed`
- Verify regions exist: `curl http://127.0.0.1:8000/api/bot/regions -H "X-BOT-TOKEN: nukus"`

### Form not advancing to next step
- Check FSM state in python bot logs
- Verify database connections
- Check Elasticsearch/Redis if used for caching

---

## Handler Files Structure

```
bot/
├── handlers/
│   ├── __init__.py           # Router exports
│   ├── start.py              # /start command, user init
│   ├── employer.py           # Employer registration form
│   ├── seeker.py             # Seeker registration form
│   └── common.py             # Shared utilities, error handling
├── keyboards.py              # Callback button builders
├── api_client.py             # Backend API client
├── config.py                 # Settings loader
├── states.py                 # FSM state definitions
└── app.py                    # Main bot application
```

---

## Complete Registration Flow Diagram

```
START (/start)
   │
   ├─→ [User Upsert] ─→ telegram_id, name, lastname, username
   │
   └─→ Role Selection (Callback)
       │
       ├─→ "Ish beruvchi" (Employer)
       │   │
       │   ├─→ Org Name (required)
       │   ├─→ Org Type (learning_center/school/kindergarten)
       │   ├─→ Region (callback)
       │   ├─→ City (optional)
       │   ├─→ District (optional)
       │   ├─→ Address (optional)
       │   ├─→ Contact Info (required)
       │   │
       │   └─→ [Employer Upsert]
       │       │
       │       └─→ ✅ DONE
       │
       └─→ "Ish qidiruvchi" (Seeker)
           │
           ├─→ Region (callback)
           ├─→ Experience (optional)
           ├─→ Salary Min (optional)
           ├─→ Work Format (online/offline/hybrid)
           ├─→ About Me (optional)
           ├─→ Seeker Type (callback)
           ├─→ Subject (callback, optional)
           ├─→ CV File (doc upload, optional)
           │
           └─→ [Seeker Upsert]
               │
               └─→ ✅ DONE
```

---

## Success Messages

After successful registration, user sees:
```
✅ Ro'yxatdan o'tdingiz
(✅ You have registered)
```

Then they can proceed to:
- View job postings
- Apply to vacancies
- Manage their profile
- Post vacancies (if employer)
- Process payments

---

## Notes

1. **Re-registration** — User can re-run `/start` to update their profile (upsert logic)
2. **Optional fields** — Use `-` to skip optional input fields
3. **Timezone** — Set to `Asia/Tashkent` in `.env`
4. **Database** — Uses MySQL (configured in `config/database.php`)
5. **Authentication** — Bot uses X-BOT-TOKEN header, separate from user authentication

