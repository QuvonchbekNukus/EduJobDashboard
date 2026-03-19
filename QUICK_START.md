# 🚀 Quick Start Guide - Bot Registration

## Pre-requisites
```bash
✅ MySQL database created (edujob)
✅ Laravel migrations run (php artisan migrate)
✅ Database seeded (php artisan db:seed)
✅ Telegram bot token obtained
✅ BOT_API_TOKEN configured
```

---

## 1️⃣ Setup Environment

### Laravel (.env)
```bash
DB_DATABASE=edujob
DB_USERNAME=root
DB_PASSWORD=

BOT_API_TOKEN=nukus
TELEGRAM_BOT_TOKEN=8054869730:AAHMP0Ge4uf08UwLAmPGARZN9Iacr29__h8
TELEGRAM_BOT_USERNAME=shimbayy_taksi_bot
BOT_API_BASE_URL=http://127.0.0.1:8000/api
```

### Python Bot (bot/.env)
```bash
BOT_TOKEN=8054869730:AAHMP0Ge4uf08UwLAmPGARZN9Iacr29__h8
API_BASE_URL=http://127.0.0.1:8000/api
BOT_API_TOKEN=nukus
```

---

## 2️⃣ Start Services

**Terminal 1 - Laravel**
```bash
php artisan serve
# Server: http://127.0.0.1:8000
```

**Terminal 2 - Python Bot**
```bash
python bot/app.py
# Bot starts polling for Telegram updates
```

---

## 3️⃣ Test User Registration

### Step 1: Send /start to Telegram Bot
- Open Telegram
- Search: `@shimbayy_taksi_bot`
- Send: `/start`

### Step 2: Choose Role
- **Ish beruvchi** (Employer) or **Ish qidiruvchi** (Seeker)

### Step 3a: If Employer
```
1. Tashkilot nomini kiriting:
   → Type: "Kimyo Markazi"

2. Tashkilot turini tanlang:
   → Select: "Maktab" or "O'quv markazi" or "Bog'cha"

3. Hududni tanlang:
   → Select: Region from list

4. Shaharni kiriting:
   → Type: "Tashkent" (or skip with "-")

5. Tumanni kiriting:
   → Type: "Yashnabad" (or skip)

6. Manzilni kiriting:
   → Type: "Mustaqillik ko'chasi 15" (or skip)

7. Aloqa ma'lumotini kiriting:
   → Type: "+99887654321" or "info@school.uz"

✅ Ro'yxatdan o'tdingiz
```

### Step 3b: If Seeker
```
1. Hududni tanlang:
   → Select: Region from list

2. Tajribani kiriting:
   → Type: "5 yillik tajriba" (or skip with "-")

3. Minimal maoshni kiriting:
   → Type: "5000000" (or skip)

4. Ish formatini tanlang:
   → Select: "Online" or "Offline" or "Hybrid"

5. O'zingiz haqingizda qisqa yozing:
   → Type: "O'qituvchi va repetitor" (or skip)

6. Lavozim turini tanlang:
   → Select: "O'qituvchi" or other type

7. Fan tanlang:
   → Select: Subject from list (or Skip)

8. CV hujjat yuboring:
   → Upload a PDF/DOC file (or skip with "-")

✅ Ro'yxatdan o'tdingiz
```

---

## 4️⃣ Verify Registration

### Check Database
```bash
# MySQL
mysql -u root edujob
SELECT * FROM users;
SELECT * FROM seekers;
SELECT * FROM employers;
```

### Check API Response
```bash
# Get user
curl http://127.0.0.1:8000/api/bot/users/1 \
  -H "X-BOT-TOKEN: nukus" | jq .

# Get seeker
curl http://127.0.0.1:8000/api/bot/seekers/1 \
  -H "X-BOT-TOKEN: nukus" | jq .

# Get employer
curl http://127.0.0.1:8000/api/bot/employers/1 \
  -H "X-BOT-TOKEN: nukus" | jq .
```

---

## 5️⃣ Common Commands

| Command | Action |
|---------|--------|
| `/start` | Start registration |
| `/cancel` | Abort and clear state |
| `Back` | Previous step |
| `Cancel` | Exit form |
| `-` | Skip optional field |
| `skip` | Skip optional field |
| `yo'q` | Skip optional field |

---

## 6️⃣ Field Reference

### Seeker (Ish qidiruvchi)
| Field | Type | Required | Example |
|-------|------|----------|---------|
| region | dropdown | ✅ | Tashkent City |
| experience | text | ❌ | 5 years teaching |
| salary_min | number | ❌ | 5000000 |
| work_format | dropdown | ✅ | online/offline/hybrid |
| about_me | text | ❌ | Experienced teacher |
| seeker_type | dropdown | ✅ | Teacher, Tutor |
| subject | dropdown | ❌ | Math, English |
| cv_file | file | ❌ | CV.pdf |

### Employer (Ish beruvchi)
| Field | Type | Required | Example |
|-------|------|----------|---------|
| org_name | text | ✅ | Kimyo Maktabi |
| org_type | dropdown | ✅ | school, learning_center, kindergarten |
| region | dropdown | ✅ | Tashkent City |
| city | text | ❌ | Tashkent |
| district | text | ❌ | Yashnabad |
| address | text | ❌ | Mustaqillik ko'chasi 15 |
| org_contact | text | ✅ | +99887654321 or username |

---

## 7️⃣ Troubleshooting

### Bot not responding
```bash
# Check if bot is running
python bot/app.py

# Check logs
tail -f bot/logs/bot.log

# Test token
python test_bot_token.py
```

### "Unauthorized" error in bot logs
```bash
# Verify token matches
echo $BOT_API_TOKEN
# Should be: nukus

# Check .env files
grep BOT_API_TOKEN .env
grep BOT_API_TOKEN bot/.env
```

### "Region not found" error
```bash
# Ensure migrations are complete
php artisan migrate --refresh --seed

# Verify regions exist
curl http://127.0.0.1:8000/api/bot/regions \
  -H "X-BOT-TOKEN: nukus" | jq .
```

### Form not advancing
```bash
# Check API is responding
curl http://127.0.0.1:8000/api/bot/users \
  -H "X-BOT-TOKEN: nukus"

# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Python logs
tail -f bot/logs/bot.log
```

---

## 8️⃣ Data Flow Example

### Complete Seeker Registration
```
1. Telegram /start
   ↓
2. BackendApiClient.upsert_user({
     telegram_id: 123456789,
     name: "Abdulloh",
     lastname: "Karimov",
     username: "abdulloh123"
   })
   ↓
3. UserController.upsert() → Creates user in database
   Returns: user_id = 5
   ↓
4. User chooses "Ish qidiruvchi" (role: seeker)
   ↓
5. Fill 8-step form, collect all data
   ↓
6. BackendApiClient.upsert_seeker({
     user_id: 5,
     region_id: 1,
     seeker_type_id: 2,
     experience: "5 yillik tajriba",
     salary_min: 5000000,
     work_format: "online",
     about_me: "O'qituvchi",
     subject_id: 3
   })
   ↓
7. SeekerController.upsert() → Creates seeker profile
   ↓
8. Database Insert:
   INSERT INTO seekers (
     user_id, region_id, seekertype_id, experience,
     salary_min, work_format, about_me, subject_id
   ) VALUES (5, 1, 2, "5 yillik tajriba", 5000000, "online", "O'qituvchi", 3)
   ↓
9. ✅ Success message: "Ro'yxatdan o'tdingiz"
```

---

## 9️⃣ Next Steps After Registration

Once registered, user can:
- ✅ View available vacancies/applications
- ✅ Apply to job postings
- ✅ Edit their profile (re-run `/start`)
- ✅ View subscription/payment options
- ✅ Post job listings (employers only)

---

## ✅ Checklist

- [ ] MySQL database created
- [ ] Migrations run
- [ ] Database seeded
- [ ] .env configured with tokens
- [ ] bot/.env configured
- [ ] Laravel server running on :8000
- [ ] Python bot running and polling
- [ ] Telegram bot token verified
- [ ] BOT_API_TOKEN set correctly
- [ ] `/start` command works on Telegram bot
- [ ] User registration completes successfully
- [ ] User data appears in database

---

**Ready to go! 🚀**

Send `/start` to bot and begin registration.

