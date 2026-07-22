# Phase 10: Microsoft Entra ID Integration - Implementation Guide

## ✅ Completed Setup

The Microsoft Entra ID (Azure AD) integration has been successfully configured with the following structure:

### 1. **Dependencies Installed**
- `laravel/socialite` - OAuth2 social authentication
- `microsoft/microsoft-graph-beta` - Microsoft Graph API support

### 2. **Database Changes**
Migration: `2026_06_23_090000_add_entra_id_fields_to_users_table.php`

New columns added to `users` table:
- `entra_id` (string, unique) - Microsoft Entra ID object ID
- `azure_tenant_id` (string) - Azure tenant identifier
- `entra_email` (string) - Email from Entra ID
- `entra_profile` (JSON) - Full Entra ID profile data
- `entra_synced_at` (timestamp) - Last sync timestamp
- `auth_provider` (string) - Authentication provider (local/entra)

### 3. **Controllers**
**File**: [app/Http/Controllers/Auth/EntraIDController.php](app/Http/Controllers/Auth/EntraIDController.php)

Routes handled:
- `redirect()` - Redirects user to Microsoft login
- `callback()` - Handles OAuth callback and user creation/update
- `logout()` - Logs out user and destroys session

### 4. **Services**
**File**: [app/Services/EntraIDUserService.php](app/Services/EntraIDUserService.php)

Key methods:
- `findOrCreateUser()` - Find or automatically create user on first login
- `createUserFromEntraID()` - Create new user from Entra ID profile
- `linkUserToEntraID()` - Link existing user to Entra ID
- `updateUserFromEntraID()` - Update user from Entra ID profile
- `syncUsersFromEntraID()` - Sync users from Entra ID directory (Graph API placeholder)

### 5. **Artisan Command**
**File**: [app/Console/Commands/SyncEntraIDUsersCommand.php](app/Console/Commands/SyncEntraIDUsersCommand.php)

Usage:
```bash
php artisan entra:sync-users
php artisan entra:sync-users --force
```

### 6. **Configuration Files**

**Services Config**: [config/services.php](config/services.php)
```php
'microsoft' => [
    'client_id' => env('ENTRA_CLIENT_ID'),
    'client_secret' => env('ENTRA_CLIENT_SECRET'),
    'redirect' => env('ENTRA_REDIRECT_URI'),
    'tenant' => env('ENTRA_TENANT_ID', 'common'),
]
```

**Entra Config**: [config/entra.php](config/entra.php)
- OAuth2 endpoints
- Scopes configuration
- Auto user provisioning settings
- User sync settings

### 7. **Routes**
**File**: [routes/auth.php](routes/auth.php)

Routes configured:
- `GET /login` - Redirect to Microsoft OAuth (route name: `login`)
- `GET /auth/entra/callback` - OAuth callback (route name: `entra.callback`)
- `POST /logout` - Logout endpoint (route name: `logout`)

Old local auth routes are commented out for reference.

### 8. **Updated User Model**
**File**: [app/Models/User.php](app/Models/User.php)

Updates:
- Added Entra ID fields to `$fillable` array
- Added `entra_profile` to `$hidden` array
- Updated `casts()` method with:
  - `entra_synced_at` → datetime
  - `entra_profile` → json

### 9. **Updated Login View**
**File**: [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php)

- Replaced local login form with "Sign in with Microsoft" button
- Shows Microsoft Entra ID information to users
- Displays error messages if login fails
- Professional blue Microsoft branding

### 10. **Environment Configuration**
**File**: [.env.example](.env.example)

Add these to your `.env` file:
```
ENTRA_CLIENT_ID=b
ENTRA_CLIENT_SECRET=
ENTRA_TENANT_ID=
ENTRA_REDIRECT_URI=http://localhost:8000/auth/entra/callback

ENTRA_AUTO_PROVISION_USERS=true
ENTRA_SYNC_ENABLED=true
ENTRA_SYNC_BATCH_SIZE=100
ENTRA_SYNC_UPDATE_EXISTING=true
```

---

## 🔧 Next Steps: Obtaining Entra ID Credentials

### To Get Your Credentials from Azure:

1. **Go to Azure Portal**: https://portal.azure.com

2. **Register an Application**:
   - Navigate to: Azure Active Directory → App registrations → New registration
   - Name: "Portal Application" (or your choice)
   - Select "Accounts in this organizational directory only"
   - Click Register

3. **Get Client ID and Tenant ID**:
   - Copy `Application (client) ID` → `ENTRA_CLIENT_ID`
   - Copy `Directory (tenant) ID` → `ENTRA_TENANT_ID`

4. **Create Client Secret**:
   - Go to: Certificates & secrets → New client secret
   - Description: "Portal App Secret"
   - Expires: 24 months (or your preference)
   - Copy the secret value → `ENTRA_CLIENT_SECRET`
   - ⚠️ **Save this immediately - you won't see it again!**

5. **Configure Redirect URI**:
   - Go to: Authentication → Add a platform → Web
   - Redirect URIs:
     ```
     http://localhost:8000/auth/entra/callback
     https://yourdomain.com/auth/entra/callback
     ```
   - Enable: "Access tokens" and "ID tokens"
   - Click Save

6. **Configure API Permissions**:
   - Go to: API permissions → Add a permission
   - Select: Microsoft Graph
   - Permissions needed:
     - `User.Read` (delegated)
     - `User.ReadBasic.All` (delegated)
     - `Directory.Read.All` (application) - for sync feature
   - Click "Grant admin consent for [Organization]"

---

## 🚀 How It Works

### Authentication Flow:

```
1. User clicks "Sign in with Microsoft"
   ↓
2. Redirected to Microsoft Entra ID login
   ↓
3. User authenticates with their Microsoft account
   ↓
4. Microsoft redirects back with authorization code
   ↓
5. Application exchanges code for access token
   ↓
6. Application fetches user profile from Microsoft Graph
   ↓
7. Application checks if user exists:
   - If exists → Update profile → Log in
   - If not exists → Create user → Log in
   ↓
8. User is logged in and redirected to dashboard
```

### User Provisioning:

When a user signs in for the first time:
1. System fetches their Entra ID profile (name, email, ID)
2. Creates a new user record with:
   - Name from Entra ID
   - Email from Entra ID
   - Entra ID object ID
   - Random secure password (not used)
   - `auth_provider` = 'entra'
   - `email_verified_at` = now (auto-verified)
3. Returns user to application

On subsequent logins:
1. User profile is updated with latest Entra ID data
2. Name and email changes are reflected if changed in Entra ID

---

## 📊 Database Fields Reference

| Field | Type | Purpose |
|-------|------|---------|
| `entra_id` | string | Unique Microsoft Entra ID object identifier |
| `azure_tenant_id` | string | Organization's Azure tenant ID |
| `entra_email` | string | Email address from Entra ID |
| `entra_profile` | json | Complete Entra ID profile response |
| `entra_synced_at` | timestamp | Last time user data was synced from Entra ID |
| `auth_provider` | string | Which provider authenticated the user (local/entra) |

---

## 🔐 Security Notes

1. **Client Secret**: Never commit to version control. Use `.env` files.
2. **Redirect URIs**: Must match exactly between Azure and your application.
3. **HTTPS Required**: For production, use HTTPS URLs only.
4. **Token Validation**: Socialite handles token validation automatically.
5. **CSRF Protection**: All OAuth routes are protected by Laravel's CSRF middleware.

---

## 🧪 Testing

### Local Testing:
```bash
# Make sure .env has test credentials
# Start the dev server
php artisan serve

# Visit: http://localhost:8000/login
```

### Test Users:
Create test users in your Azure AD directory:
- Test Admin: admin@yourtenant.onmicrosoft.com
- Test User: user@yourtenant.onmicrosoft.com

---

## 📝 Available Commands

```bash
# Run user sync from Entra ID
php artisan entra:sync-users

# Force sync even if recently synced
php artisan entra:sync-users --force

# Check migrations status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback
```

---

## 🆘 Troubleshooting

### "Invalid state" error:
- Clear browser cookies and session
- Verify ENTRA_REDIRECT_URI matches exactly in Azure

### User not created:
- Check if auto-provisioning is enabled: `ENTRA_AUTO_PROVISION_USERS=true`
- Review application logs for errors
- Verify user doesn't already exist with that email

### Login redirects in loop:
- Check `ENTRA_TENANT_ID` is correct
- Verify client secret hasn't expired in Azure
- Ensure Redirect URI is configured in both locations

### Permission errors:
- Go back to Azure → API permissions
- Click "Grant admin consent"
- Wait a few minutes for permissions to propagate

---

## 📚 Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Microsoft Graph API](https://learn.microsoft.com/en-us/graph/overview)
- [Azure AD OAuth 2.0 Flow](https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-auth-code-flow)
- [Azure App Registration Guide](https://learn.microsoft.com/en-us/azure/active-directory/develop/quickstart-register-app)

---

## ✨ Features Implemented

✅ Microsoft Entra ID OAuth 2.0 integration
✅ Automatic user provisioning on first login
✅ User profile syncing
✅ Secure session management
✅ Graceful logout functionality
✅ Error handling with user feedback
✅ Artisan command for manual sync
✅ Configuration management
✅ Commented-out local auth routes for reference
✅ Professional login interface with Microsoft branding

---

**Status**: ⏳ **WAITING FOR YOUR ENTRA ID CREDENTIALS**

Once you provide your Entra ID credentials, update the `.env` file and the system will be ready to use!
