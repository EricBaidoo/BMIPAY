# BMI Pay

Lightweight payment facilitator built with PHP, HTML, CSS (Bootstrap) and JS. Supports Paystack, PayPal and Zelle as payment endpoints. This repository is intended to act as the public-facing payment portal that your store redirects to.

Security notes
- The project expects a `config.php` with a secret key. Keep `config.php` out of version control (it's in `.gitignore`).
- The platform validates an HMAC hash on incoming payment links to ensure name/email/amount were not tampered with.

Files of interest
- `index.php` — home/entry page. Accepts `user_name`, `user_email`, `amount` as URL parameters and builds signed links to payment pages.
- `payments/paystack.php` — Paystack/mobile money form and verification.
- `payments/paypal.php` — International (PayPal/Zelle) form and verification.
- `assets/style.css` — styles.

How to push to GitHub
1. Create a new empty repository on GitHub (no README) and copy the repo URL (HTTPS or SSH).
2. On your machine, add the remote and push:

```powershell
cd C:\xampp\htdocs\BMIPAY
git remote add origin <YOUR_GIT_REMOTE_URL>
git branch -M main
git push -u origin main
```

If you want me to push the code for you, provide the repository URL and confirm and I will add the remote and push from this environment.
