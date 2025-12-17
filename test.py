import hashlib
import requests

# آپ اپنا پسندیدہ password یہاں رکھیں
NEW_PASSWORD = "Admin@2024"  # اپنا نیا password یہاں لکھیں

# Hash بنائیں
password_hash = hashlib.sha256(NEW_PASSWORD.encode('utf-8')).hexdigest()

print("="*60)
print("RESETTING ADMIN PASSWORD")
print("="*60)
print(f"New Password: {NEW_PASSWORD}")
print(f"New Hash: {password_hash}")
print("="*60)

# Supabase میں update کریں
url = "https://wzvsmzjfthyklbpahqip.supabase.co/rest/v1/users"
headers = {
    "apikey": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Ind6dnNtempmdGh5a2xicGFocWlwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjU0MTU0MDQsImV4cCI6MjA4MDk5MTQwNH0.GHUwOnlBVZPynzfYZTLv8MdU41WLKRRodql466E5hqQ",
    "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Ind6dnNtempmdGh5a2xicGFocWlwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjU0MTU0MDQsImV4cCI6MjA4MDk5MTQwNH0.GHUwOnlBVZPynzfYZTLv8MdU41WLKRRodql466E5hqQ",
    "Content-Type": "application/json",
    "Prefer": "return=minimal"
}

# Update data
data = {
    "password": password_hash
}

# Update user with email waseem@sitefixstudio.com
response = requests.patch(
    f"{url}?email=eq.waseem@sitefixstudio.com",
    headers=headers,
    json=data
)

if response.status_code == 204:
    print("\n✅ SUCCESS! Password has been reset.")
    print("\n📋 Login Credentials:")
    print(f"   Email: waseem@sitefixstudio.com")
    print(f"   Password: {NEW_PASSWORD}")
    print("\n🔐 Save these credentials securely!")
else:
    print(f"\n❌ ERROR: {response.status_code}")
    print(f"Response: {response.text}")