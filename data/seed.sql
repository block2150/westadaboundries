TRUNCATE TABLE profile_images;
TRUNCATE TABLE profile_settings;
TRUNCATE TABLE users;

insert into users (uuid, username, email, password, contributor, created, lastlogin, optin) values ('fcd07723-d9e8-11e2-9895-00ff893578ce', 'block2150', 'block2150@gmail.com', 'beaver', 1, NOW(), NOW(), 1);

insert into profile_settings (user_id, public_profile, show_descriptions) values ('fcd07723-d9e8-11e2-9895-00ff893578ce', 1, 0);
