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


--  分类
-- create table article_category
-- (
--     id int unsigned not null auto_increment comment 'ID',
--     cat_name VARCHAR(255) not null comment '分类名称',
--     primary key (id)
-- )engine='InnoDB' comment='分类表';


-- insert into article_category(id,cat_name) VALUES
-- (1,'外语学习'),
-- (2,'哲学'),
-- (3,'小说'),
-- (4,'人工智能'),
-- (5,'JavaScript'),
-- (6,'php'),
-- (7,'运动健身'),
-- (8,'心理学'),
-- (9,'宗教'),
-- (10,'法律'),
-- (11,'程序设计'),
-- (12,'大数据');



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

INSERT INTO category VALUES
    (1,'家用电器',0,'-'),
        (3,'空调',1,'-1-'),
            (7,'柜式空调',3,'-1-3-'),
            (8,'中央空调',3,'-1-3-'),
        (4,'冰箱',1,'-1-'),
            (9,'多门',4,'-1-4-'),
            (10,'对门',4,'-1-4-'),
    (2,'电脑',0,'-'),
        (5,'电脑整机',2,'-2-'),
            (11,'笔记本',5,'-2-5-'),
            (12,'游戏本',5,'-2-5-'),
        (6,'电脑配件',2,'-2-'),
            (13,'显示器',6,'-2-6-'),
            (14,'CPU',6,'-2-6-');