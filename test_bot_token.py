#!/usr/bin/env python3
import asyncio
import httpx
import json
from bot.config import Settings

async def test_bot_token():
    settings = Settings()
    token = settings.bot_token
    print(f"\n🔍 Bot Token Tekshirish\n" + "="*50)
    print(f"Token: {token[:20]}...{token[-10:]}")
    print("="*50)
    
    async with httpx.AsyncClient(verify=False) as client:
        try:
            url = f"https://api.telegram.org/bot{token}/getMe"
            print(f"\n📨 API Manzili: {url}")
            response = await client.get(url, timeout=10)
            
            print(f"\n✅ Status Code: {response.status_code}")
            
            data = response.json()
            print(f"✅ Response: {json.dumps(data, indent=2, ensure_ascii=False)}")
            
            if data.get("ok"):
                bot_info = data.get("result", {})
                print(f"\n🤖 Bot Ma'lumotlari:")
                print(f"  • ID: {bot_info.get('id')}")
                print(f"  • Nomi: {bot_info.get('first_name')}")
                print(f"  • Username: @{bot_info.get('username')}")
                print(f"  • Bot: {bot_info.get('is_bot')}")
                print(f"\n✅ TOKEN TO'G'RI! Bot faol va ishlayapti.")
            else:
                print(f"\n❌ XATO: {data.get('description', 'Noma\'lum xato')}")
                
        except Exception as e:
            print(f"\n❌ Xato: {str(e)}")

if __name__ == "__main__":
    asyncio.run(test_bot_token())
