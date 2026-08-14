# Microsoft Entra ID Setup Guide

OnePortal uses Microsoft Entra ID (Azure AD) as its sign-in method — there's no local registration form. This guide covers how to register an Azure AD application for your organization and configure the app to use it. No source changes are required; everything is driven by environment variables, so the same codebase works for any company's tenant.

---

## How It Works

```
1. User clicks "Sign in with Microsoft"
   ↓
2. Redirected to Microsoft Entra ID login
   ↓
3. User authenticates with their Microsoft account
   ↓
4. Microsoft redirects back with an authorization code
   ↓
5. App exchanges the code for an access token
   ↓
6. App fetches the user's profile from Microsoft Graph
   ↓
7. App finds or creates a local user record
   ↓
8. User is logged in and redirected to the dashboard
```

On first login, if auto-provisioning is enabled, a new `User` record is created from the Entra ID profile (name, email, object ID) with `auth_provider = entra` and an auto-verified email. On every later login, the local profile is refreshed with the latest name/email from Entra ID.

---

## 1. Register an Application in Azure

1. Go to [portal.azure.com](https://portal.azure.com).
2. Navigate to **Azure Active Directory → App registrations → New registration**.
3. Name it (e.g. "OnePortal").
4. Select **Accounts in this organizational directory only**.
5. Click **Register**.

## 2. Copy the IDs

From the app's **Overview** page:

| Value | Goes into |
|---|---|
| Application (client) ID | `ENTRA_CLIENT_ID` |
| Directory (tenant) ID | `ENTRA_TENANT_ID` |

## 3. Create a Client Secret

1. Go to **Certificates & secrets → New client secret**.
2. Give it a description and an expiry (e.g. 24 months).
3. Copy the **secret value** immediately into `ENTRA_CLIENT_SECRET` — it will not be shown again.

## 4. Configure the Redirect URI

1. Go to **Authentication → Add a platform → Web**.
2. Add the redirect URI(s):
   ```
   http://localhost:8000/auth/entra/callback
   https://yourdomain.com/auth/entra/callback
   ```
3. Enable **Access tokens** and **ID tokens**.
4. Save.

## 5. Grant API Permissions

1. Go to **API permissions → Add a permission → Microsoft Graph**.
2. Add:
   - `User.Read` (delegated)
   - `User.ReadBasic.All` (delegated)
   - `Directory.Read.All` (application — needed only for the directory sync command)
3. Click **Grant admin consent** for the organization.

---

## 6. Configure the App

Set these in your `.env` file:

```env
ENTRA_CLIENT_ID=
ENTRA_CLIENT_SECRET=
ENTRA_TENANT_ID=
ENTRA_REDIRECT_URI=http://localhost:8000/auth/entra/callback

ENTRA_AUTO_PROVISION_USERS=true
ENTRA_SYNC_ENABLED=true
ENTRA_SYNC_BATCH_SIZE=100
ENTRA_SYNC_UPDATE_EXISTING=true
```

These map to [config/entra.php](config/entra.php) and the `microsoft` block in [config/services.php](config/services.php). Leaving `ENTRA_TENANT_ID` unset defaults to `common` (any Microsoft account), which is rarely what you want for an internal portal — set it to your organization's tenant ID to restrict sign-in to your directory.

---

## Where This Lives in the Codebase

| Piece | File |
|---|---|
| OAuth redirect / callback / logout | [app/Http/Controllers/Auth/EntraIDController.php](app/Http/Controllers/Auth/EntraIDController.php) |
| Find/create/update/sync user logic | [app/Services/EntraIDUserService.php](app/Services/EntraIDUserService.php) |
| Manual directory sync command | [app/Console/Commands/SyncEntraIDUsersCommand.php](app/Console/Commands/SyncEntraIDUsersCommand.php) |
| Routes | [routes/auth.php](routes/auth.php) — `GET /login`, `GET /auth/entra/callback`, `POST /logout` |
| Config | [config/entra.php](config/entra.php), [config/services.php](config/services.php) |
| User model fields | `entra_id`, `azure_tenant_id`, `entra_email`, `entra_profile` (JSON), `entra_synced_at`, `auth_provider` |

---

## Manual User Sync

To pull/update users from the Entra ID directory in bulk (requires `Directory.Read.All`):

```bash
php artisan entra:sync-users
php artisan entra:sync-users --force   # sync even if recently synced
```

---

## Testing Locally

```bash
php artisan serve
```

Visit `http://localhost:8000/login` and sign in with a test account from your Azure AD tenant (e.g. `user@yourtenant.onmicrosoft.com`).

---

## Troubleshooting

**"Invalid state" error**
Clear browser cookies/session, and double-check `ENTRA_REDIRECT_URI` matches exactly what's registered in Azure.

**User not created on login**
Confirm `ENTRA_AUTO_PROVISION_USERS=true`, check the application logs, and verify no existing user already has that email.

**Login redirects in a loop**
Verify `ENTRA_TENANT_ID` is correct and the client secret hasn't expired in Azure.

**Permission / consent errors**
Go back to **API permissions** in Azure, click **Grant admin consent**, and wait a few minutes for it to propagate.

---

## Security Notes

- Never commit `ENTRA_CLIENT_SECRET` or any real credentials — keep them in `.env`, which is gitignored.
- Redirect URIs must match exactly between Azure and the app's configuration.
- Use HTTPS redirect URIs in production.
- Laravel's CSRF middleware protects all OAuth routes; Socialite handles token validation.

---

## Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Microsoft Graph API](https://learn.microsoft.com/en-us/graph/overview)
- [Azure AD OAuth 2.0 Authorization Code Flow](https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-auth-code-flow)
- [Azure App Registration Quickstart](https://learn.microsoft.com/en-us/azure/active-directory/develop/quickstart-register-app)
