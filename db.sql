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


drop table if EXISTS article_img;
create table article_img
(
    id int unsigned not null auto_increment comment 'ID',
    url VARCHAR(255) not null comment '图片路径',
    big_url VARCHAR(255) not null comment '大图片',
    middle_url VARCHAR(255) not null comment '中图片',
    small_url VARCHAR(255) not null comment '小图片',
    article_id int unsigned not null comment '文章id',
    key article_id(article_id),
    PRIMARY key (id)
)engine='InnoDB' comment='文章图片';


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



--  RBAC

-- privilage
drop table if exists privilage;
create table privilage
(
    id int unsigned not null auto_increment comment 'ID',
    pri_url varchar(255) not null comment '权限路径',
    pri_name VARCHAR(255) not null comment '权限名称',
    parent_id int unsigned not null DEFAULT '0' comment '上级权限的id',
    primary key (id) 
)engine='InnoDB' comment='权限表';

insert into privilage(id,pri_url,pri_name,parent_id) VALUES
    (1,'/','常用操作',0),
        (2,'/category/index','分类管理',1),
            (3,'/category/insert','添加分类',2),
            (4,'/category/modify','编辑分类',2),
            (5,'/category/delete','删除分类',2),
        (6,'/article/index','作品管理',1),
            (7,'/article/insert','添加分类',6),
            (8,'/article/modify','编辑分类',6),
            (9,'/article/delete','删除分类',6),
        (10,'/log/pageview','PV',1),
            (11,'/log/delete','删除PV',10);

        insert into role_privilage(pri_id,role_id) VALUES
        (6,2),
        (7,2),
        (8,2),
        (9,2),
        (1,3),
        (2,3),
        (3,3),
        (4,3),
        (5,3),
        (6,3),
        (7,3),
        (8,3),
        (9,3);

        insert into role(id,role_name) VALUES
        (1,'超级管理员'),
        (2,'作品总监'),
        (3,'分类总监');

        insert into admin_role(role_id,admin_id) VALUES
        (1,1),
        (3,2),
        (2,3);

        insert into admin(id,admin_name,admin_password) VALUES
        (1,'root','21232f297a57a5a743894a0e4a801fc3'),
        (2,'tom','21232f297a57a5a743894a0e4a801fc3'),
        (3,'jack','21232f297a57a5a743894a0e4a801fc3');

--  中间表
drop table if exists role_privilage;
create table role_privilage
(
    pri_id int unsigned not null comment '权限ID',
    role_id int unsigned not null comment '角色ID',
    key pri_id(pri_id),
    key role_id(role_id)
)engine='InnoDB' comment='角色权限表';

-- role

drop table if exists role;
create table role
(
    id int unsigned not null auto_increment comment 'ID',
    role_name VARCHAR(255) not null comment '角色名称',
    primary key (id)
)engine='InnoDB' comment="角色表";

--  中间表
drop table if exists admin_role;
create table admin_role
(
    role_id int unsigned not null comment '角色ID',
    admin_id int unsigned not null comment '管理员id',
    key role_id(role_id),
    key admin_id(admin_id)
)engine='InnoDB' comment='管理角色表';

-- admin
drop TABLE if exists admin;
create table admin
(
    id int unsigned not null auto_increment comment 'ID',
    admin_name VARCHAR(255) not null comment '管理员名称',
    admin_password VARCHAR(255) not null comment '密码',
    primary key (id) 
)engine='InnoDB' comment='管理员表';