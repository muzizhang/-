create table user(
    id int unsigned not null auto_increment comment 'ID',
    password VARCHAR(255) not null comment '密码',
    username VARCHAR(11) not null comment '手机号/邮箱',
    primary key (id)
)engine='InnoDB' comment="用户表";

-- 一个用户对应多篇文章   1:M  
-- 一篇文章对应一个用户   1:1
--   1:M
drop table if exists article;
create table article 
(
    id int unsigned not null auto_increment comment 'ID',
    title VARCHAR(255) not null comment '标题',
    content longText not null comment '内容',
    logo VARCHAR(255) not null comment '缩略图',
    author VARCHAR(255) not null comment '作者',
    user_id int unsigned not null comment '用户id',
    cat1_id int unsigned not null comment '分类id',
    cat2_id int unsigned not null comment '二级id',
    cat3_id int unsigned not null comment '三级id',
    created_at datetime not null default current_timestamp comment '发表时间',
    updated_at timestamp not null DEFAULT current_timestamp ON UPDATE current_timestamp comment '更新时间',
    primary key (id)
)engine='InnoDB' comment='文章表';



create table userLog
(
    id int unsigned not null auto_increment comment 'ID',
    ip VARCHAR(255) not null  comment '用户ip',
    equipment VARCHAR(255) not null comment '登录设备',
    browser VARCHAR(255) not null comment '使用的浏览器',
    user_id int unsigned not null comment '用户id',
    created_at DATETIME not null DEFAULT current_timestamp comment '登录时间',
    key user_id(user_id),
    key created_at(created_at),
    primary key (id)
)engine='InnoDB' comment='用户登录记录表';

create table Log
(
    id int unsigned not null auto_increment comment 'ID',
    path VARCHAR(255) not null comment '访问路径',
    created_at DATETIME not null DEFAULT current_timestamp comment '访问时间',
    user_id int unsigned comment '用户id',
    key path(path),
    key created_at(created_at),
    key user_id(user_id),
    primary key (id)
)engine='InnoDB' comment='日志表';



--   分类表
drop table if exists category;
create table category
(
    id int unsigned not null auto_increment comment 'ID',
    cate_name VARCHAR(255) not null comment '分类名称',
    parent_id int unsigned not null default 0 comment '父级id',
    path VARCHAR(255) not null default '-' comment '所有上级分类的id',
    primary key (id)
)engine='InnoDB' comment='分类表';

insert into category(id,cate_name,parent_id,path) VALUES
    (1,'书屋',0,'-'),
        (2,'法律',1,'-1-'),
            (3,'法律工具书',2,'-1-2-'),
            (4,'法学理论',2,'-1-2-'),
            (5,'法律知识读物',2,'-1-2-'),
            (6,'司法案例',2,'-1-2-'),
            (7,'司法解释',2,'-1-2-'),
        (8,'政治/军事',1,'-1-'),
            (9,'政治',8,'-1-8-'),
            (10,'政治理论',8,'-1-8-'),
            (11,'中国政治',8,'-1-8-'),
            (12,'世界政治',8,'-1-8-'),
        (13,'社会科学',1,'-1-'),
            (14,'社会科学总论',13,'-1-13-'),
            (15,'文化人类学',13,'-1-13-'),
            (16,'语言文字',13,'-1-13-'),
            (17,'心理学',13,'-1-13-'),
        (18,'学习',1,'-1-'),
            (19,'成人自考',18,'-1-18-'),
            (20,'艺术',18,'-1-18-');