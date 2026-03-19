# ⚠️ BOT ISHLAMAVGA SABAB - PYTHON NI'STALLANSIZ

## 🔴 MUAMMOSI:
**Python to'liq o'rnatilmagan yoki PATH'da yo'q.**

---

## ✅ FIX QI'LISH UCHUN:

### Step 1: Python'ni o'rnatish
1. **Sitedan yuklab oling**: https://www.python.org/downloads/
2. **Installer'ni oching** (masalan: `python-3.11.8-amd64.exe`)
3. **MUHIM: "Add Python to PATH" checkboxni TIKLANG** ✓
4. **"Install Now" ni bosing**

### Step 2: Python'ni tekshirish
```bash
python --version
# Javob bo'ladi: Python 3.x.x
```

### Step 3: Bot'ni ishga tushirish
```bash
cd c:\xampp\htdocs\EduJobDashboard\bot
python app.py
```

**Kutilgan output:**
```
[INFO] Starting test bot...
[INFO] Bot connected, starting polling...
```

### Step 4: Telegram-da test qiling
- Telegram-da bot'ni toping: `@shimbayy_taksi_bot`
- Yuborish: `/start`
- Kutilgan javob: Savollar va registratsiya formi

---

## 🆘 Agar hali ham ishlamasa:

### Option A: Batch script'ni ishga tushiring
```bash
cd c:\xampp\htdocs\EduJobDashboard\bot
run_bot.bat
```
Bu script o'zi Python'ni tekshirib, agar kerak bo'lsa packages'ni o'rnatadi.

### Option B: PowerShell bilan
```powershell
cd c:\xampp\htdocs\EduJobDashboard\bot
python -m pip install -r requirements.txt
python app.py
```

### Option C: Manual install
```powershell
# Packages'ni o'rnatish
python -m pip install aiogram>=3.7 httpx>=0.27 python-dotenv>=1.0

# Bot'ni ishga tushirish
python app.py
```

---

## 📋 Tekshirish ro'yxati:

- [ ] Python installed va PATH'da
- [ ] `python --version` javob beradi
- [ ] `python -c "import aiogram"` xato ko'rsatmaydi
- [ ] `bot/.env` file to'g'ri configured
- [ ] Laravel API running (port 8000)
- [ ] Bot logs ko'rinishi (STARTING/Polling)

---

## 🔗 BILGILER:

| Parametr | Qiymat |
|----------|--------|
| Python Version | 3.8+ (6-7 yuqori) |
| Bot Token | ✅ `8054869730:AAHMP0Ge4uf08UwLAmPGARZN9Iacr29__h8` |
| API Token | ✅ `nukus` |
| API URL | ✅ `http://127.0.0.1:8000/api` |

---

## 💡 O'ZGARTIRILGAN FAYLLAR:

✅ `/bot/.env` - Tokens bilan japlandi  
✅ `/bot/test_bot.py` - Test bot yaratildi  
✅ `/bot/run_bot.bat` - Batch installer yaratildi  
✅ `/bot/debug.py` - Debug script yaratildi

---

## 📞 Agar hali problem bo'lsa:

1. **Python version'ni tekshiring**: `python --version`
2. **Packages'ni verify qiling**: `python -m pip list | findstr aiogram`
3. **Bot error'larni ko'ring**: `python app.py` (foreground'da output'ni ko'ring)
4. **Logs'ni o'qing**: `cat bot/logs/bot.log`

---

**ASOSIY: Python KERAK! Uni o'rnatgandan keyin hamma issiqlar hal bo'ladi!** 🚀
