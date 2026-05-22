-- Admin account must be created with a valid password_hash().
-- Do NOT paste a random hash — use the PHP installer instead:
--
--   http://localhost/emotimate/setup/install_admin_now.php
--
-- Or open setup/create_admin.php and click "Admin hesabı oluştur".

USE emotimate;

-- Manual role fix only (if account exists but role is wrong):
-- UPDATE users SET role = 'admin' WHERE email = 'admin@emotimate.com';
