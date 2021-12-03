drop database if exists upcolor;
create database upcolor default character set utf8 collate utf8_general_ci;
grant all on upcolor.* to 'staff'@'localhost' identified by 'password';
use upcolor;

create table couses (
    couse_id int auto_increment primary key,
    couse_name varchar(255) not null
);

create table accounts (
    user_id int auto_increment primary key,
    user_name varchar(255) not null unique,
    user_mail varchar(255) not null unique,
    password varchar(255) not null,
    created_at datetime DEFAULT current_timestamp(),
    updated_at timestamp DEFAULT current_timestamp(),
    profile_img varchar(255),
    bg_color varchar(7),
    couse_id int,
    github_account varchar(255),
    introduction varchar(1000),
    project_num int,
    template_id int not null,
    release_flg int not null
);

create table programming_lans (
    user_id int not null,
    programming_lan varchar(255) not null,
    foreign key(user_id) references accounts(user_id) on delete cascade
);

create table qual (
    user_id int not null,
    qual_name varchar(255) not null,
    foreign key(user_id) references accounts(user_id) on delete cascade
);

create table projects (
    user_id int not null,
    project_title varchar(255) not null,
    project_img varchar(255),
    foreign key(user_id) references accounts(user_id) on delete cascade
);

create table chat (
    chat_id int auto_increment primary key,
    user_id int not null,
    receiver_id int not null,
    chat_text varchar(1000) not null,
    send_at datetime DEFAULT current_timestamp(),
    foreign key(user_id) references accounts(user_id) ON DELETE CASCADE,
    foreign key(user_id) references accounts(user_id) ON DELETE CASCADE
);

create table last_updates (
    user_id int not null,
    receiver_id int not null,
    chat_id int not null,
    primary key(user_id, receiver_id),
    foreign key(chat_id) references chat(chat_id) ON DELETE CASCADE
);

create table favorite (
    user_id int not null,
    favorited_id int not null,
    primary key(user_id, favorited_id),
    foreign key(user_id) references accounts(user_id) ON DELETE CASCADE,
    foreign key(favorited_id) references accounts(user_id) ON DELETE CASCADE
);

insert into accounts(user_name, user_mail, password, profile_img, bg_color, couse_id, template_id, release_flg) values
('Genki', '2112059@i-seifu.jp', '2021gakusei', '../../profile_images/onishi.jpg', '#2f4f4f', 1, 1, 1),
('arao', '1111111@i-seifu.jp', '2021gakusei', '../../profile_images/dog1.jpg', '#191970', 3, 1, 1),
('inui', '1111112@i-seifu.jp', '2021gakusei', '../../profile_images/dog2.jpg', '#fffafa', 2, 1, 1),
('nishant', '1111113@i-seifu.jp', '2021gakusei', '../../profile_images/dog3.jpg', '#98fb98', 1, 1, 1),
('print', '1111114@i-seifu.jp', '2021gakusei', '../../profile_images/dog4.jpg', '#ffb6c1', 3, 1, 1),
('dog', '1111115@i-seifu.jp', '2021gakusei', '', '#ffff00', 2, 1, 1),
('cap', '1111116@i-seifu.jp', '2021gakusei', '', '#ff00ff', 4, 1, 1),
('komeda', '1111117@i-seifu.jp', '2021gakusei', '', '#00fa9a', 1, 1, 1),
('apple', '1111118@i-seifu.jp', '2021gakusei', '', '#00bfff', 4, 1, 1),
('orange', '1111119@i-seifu.jp', '2021gakusei', '', '#b22222', 1, 1, 1),
('test1', 'test1@i-seifu.jp', '$2y$10$HYmP3YBwtXQN4AbYOyqXVOzTlN5nmcFYVkRsmxx1JG/2SiGveYspW', '../../profile_images/test1.png', '#66fcff', 1, 1, 1),
('test2', 'test2@i-seifu.jp', '$2y$10$cYWm8iqzRRUE5EmH9FV4aOpP/mjbneqd2Gvu1hV2uCv5U9oTaTBV.', '../../profile_images/test2.png', '#4bf529', 1, 1, 1),
('test3', 'test3@i-seifu.jp', '$2y$10$ZUG5x90YeFMmfDbNEWvWX.PYTAESDzowU6K13F2jFVrewpphKMYAi', '../../profile_images/test3.jpeg', '#f59ed1', 1, 1, 1);

insert into couses values(null, "本科");
insert into couses values(null, "情報処理専攻");
insert into couses values(null, "ゲーム専攻");
insert into couses values(null, "デザイン専攻");
insert into couses values(null, "ハードウェア専攻");

insert into programming_lans values(1, 'C');
insert into programming_lans values(1, 'php');
insert into programming_lans values(1, 'sql');
insert into programming_lans values(1, 'python');
insert into programming_lans values(2, 'C');
insert into programming_lans values(2, 'php');
insert into programming_lans values(2, 'python');
insert into programming_lans values(3, 'C');
insert into programming_lans values(4, 'C');
insert into programming_lans values(4, 'python');