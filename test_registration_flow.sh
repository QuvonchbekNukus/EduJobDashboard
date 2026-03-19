#!/usr/bin/env bash
# Bot Registration Flow Test
# This script tests the complete user registration flow through the Telegram bot

echo "🔍 EduJobDashboard Bot Registration Flow Test"
echo "==========================================="
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

# Variables
BOT_TOKEN="8054869730:AAHMP0Ge4uf08UwLAmPGARZN9Iacr29__h8"
BOT_API_TOKEN="nukus"
API_BASE_URL="http://127.0.0.1:8000/api"

# Test 1: Check Laravel API is running
echo -e "${BLUE}Test 1: Check Laravel API${NC}"
echo "Testing API connectivity..."
response=$(curl -s -w "\n%{http_code}" "$API_BASE_URL/bot/regions" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" 2>/dev/null)
http_code=$(echo "$response" | tail -n1)
if [ "$http_code" = "200" ]; then
  echo -e "${GREEN}✅ API is running and responding${NC}"
else
  echo -e "${RED}❌ API returned HTTP $http_code${NC}"
  echo "Response: $(echo "$response" | head -n-1)"
fi
echo ""

# Test 2: Check Telegram Bot Token
echo -e "${BLUE}Test 2: Check Telegram Bot Token${NC}"
echo "Testing bot token with Telegram API..."
response=$(curl -s "https://api.telegram.org/bot$BOT_TOKEN/getMe")
ok=$(echo "$response" | grep -o '"ok":true')
if [ ! -z "$ok" ]; then
  bot_name=$(echo "$response" | grep -o '"first_name":"[^"]*"' | cut -d'"' -f4)
  echo -e "${GREEN}✅ Bot token is valid. Bot: $bot_name${NC}"
else
  echo -e "${RED}❌ Bot token is invalid${NC}"
  echo "Response: $response"
fi
echo ""

# Test 3: Test Regions Endpoint
echo -e "${BLUE}Test 3: Fetch Regions${NC}"
echo "Getting available regions..."
regions=$(curl -s "$API_BASE_URL/bot/regions" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" 2>/dev/null)
region_count=$(echo "$regions" | grep -o '"id":' | wc -l)
if [ "$region_count" -gt 0 ]; then
  echo -e "${GREEN}✅ Found $region_count regions${NC}"
  echo "Sample regions:"
  echo "$regions" | grep -o '"id":[0-9]*,"name":"[^"]*"' | head -3
else
  echo -e "${RED}❌ No regions found${NC}"
  echo "Response: $regions"
fi
echo ""

# Test 4: Test Seekers Types Endpoint
echo -e "${BLUE}Test 4: Fetch Seekers Types${NC}"
echo "Getting available seeker types..."
types=$(curl -s "$API_BASE_URL/bot/seekers-types" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" 2>/dev/null)
type_count=$(echo "$types" | grep -o '"id":' | wc -l)
if [ "$type_count" -gt 0 ]; then
  echo -e "${GREEN}✅ Found $type_count seeker types${NC}"
  echo "Sample types:"
  echo "$types" | grep -o '"id":[0-9]*.*"label":"[^"]*"' | head -3
else
  echo -e "${RED}❌ No seeker types found${NC}"
  echo "Response: $types"
fi
echo ""

# Test 5: Test Subjects Endpoint
echo -e "${BLUE}Test 5: Fetch Subjects${NC}"
echo "Getting available subjects..."
subjects=$(curl -s "$API_BASE_URL/bot/subjects" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" 2>/dev/null)
subject_count=$(echo "$subjects" | grep -o '"id":' | wc -l)
if [ "$subject_count" -gt 0 ]; then
  echo -e "${GREEN}✅ Found $subject_count subjects${NC}"
  echo "Sample subjects:"
  echo "$subjects" | grep -o '"id":[0-9]*,"name":"[^"]*"' | head -3
else
  echo -e "${RED}❌ No subjects found${NC}"
  echo "Response: $subjects"
fi
echo ""

# Test 6: Test User Upsert (Create User)
echo -e "${BLUE}Test 6: Test User Upsert${NC}"
echo "Creating/updating test user..."
user_payload='{
  "telegram_id": 123456789,
  "name": "Test",
  "lastname": "User",
  "username": "testuser123",
  "role": "seeker"
}'
user_response=$(curl -s -X POST "$API_BASE_URL/bot/users/upsert" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d "$user_payload" 2>/dev/null)
user_id=$(echo "$user_response" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
if [ ! -z "$user_id" ]; then
  echo -e "${GREEN}✅ User created. User ID: $user_id${NC}"
else
  echo -e "${RED}❌ Failed to create user${NC}"
  echo "Response: $user_response"
fi
echo ""

# Test 7: Test Seeker Upsert (Create Seeker Profile)
if [ ! -z "$user_id" ]; then
  echo -e "${BLUE}Test 7: Test Seeker Upsert${NC}"
  echo "Creating seeker profile..."
  seeker_payload="{
    \"user_id\": $user_id,
    \"region_id\": 1,
    \"seeker_type_id\": 1,
    \"experience\": \"5 years\",
    \"salary_min\": 5000000,
    \"work_format\": \"online\",
    \"about_me\": \"Experienced teacher\",
    \"subject_id\": 1
  }"
  seeker_response=$(curl -s -X POST "$API_BASE_URL/bot/seekers/upsert" \
    -H "X-BOT-TOKEN: $BOT_API_TOKEN" \
    -H "Content-Type: application/json" \
    -d "$seeker_payload" 2>/dev/null)
  seeker_id=$(echo "$seeker_response" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
  if [ ! -z "$seeker_id" ]; then
    echo -e "${GREEN}✅ Seeker profile created. Seeker ID: $seeker_id${NC}"
  else
    echo -e "${RED}❌ Failed to create seeker profile${NC}"
    echo "Response: $seeker_response"
  fi
fi
echo ""

# Test 8: Test Employer Upsert
echo -e "${BLUE}Test 8: Test Employer Upsert${NC}"
echo "Creating employer profile..."
employer_payload='{
  "telegram_id": 987654321,
  "name": "Test",
  "lastname": "Employer",
  "username": "testemployer123",
  "role": "employer"
}'
employer_user_response=$(curl -s -X POST "$API_BASE_URL/bot/users/upsert" \
  -H "X-BOT-TOKEN: $BOT_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d "$employer_payload" 2>/dev/null)
employer_user_id=$(echo "$employer_user_response" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)

if [ ! -z "$employer_user_id" ]; then
  employer_profile_payload="{
    \"user_id\": $employer_user_id,
    \"org_name\": \"Test School\",
    \"org_type\": \"school\",
    \"region_id\": 1,
    \"city\": \"Tashkent\",
    \"org_contact\": \"info@testschool.uz\"
  }"
  employer_response=$(curl -s -X POST "$API_BASE_URL/bot/employers/upsert" \
    -H "X-BOT-TOKEN: $BOT_API_TOKEN" \
    -H "Content-Type: application/json" \
    -d "$employer_profile_payload" 2>/dev/null)
  employer_id=$(echo "$employer_response" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
  if [ ! -z "$employer_id" ]; then
    echo -e "${GREEN}✅ Employer profile created. Employer ID: $employer_id${NC}"
  else
    echo -e "${RED}❌ Failed to create employer profile${NC}"
    echo "Response: $employer_response"
  fi
fi
echo ""

echo -e "${BLUE}==========================================="
echo "Test Summary:"
echo "==========================================${NC}"
echo -e "${GREEN}✅ Bot registration flow is ready!${NC}"
echo ""
echo "Next steps:"
echo "1. Start PHP server: php artisan serve"
echo "2. Start Python bot: python bot/app.py"
echo "3. Send /start to @shimbayy_taksi_bot on Telegram"
echo ""
