-- Add profile photo column to users table
ALTER TABLE `users` ADD COLUMN `profile_photo` VARCHAR(500) NULL DEFAULT NULL AFTER `status`;

-- Create indexes for performance
ALTER TABLE `users` ADD INDEX idx_profile_photo (profile_photo);
