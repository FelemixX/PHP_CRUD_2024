create table if not exists city
(
    ID     int unsigned auto_increment
        primary key,
    NAME   varchar(25) not null,
    REGION varchar(50) not null
);

create table if not exists classification_of_pharmacy_products
(
    ID           int unsigned auto_increment
        primary key,
    PRESCRIPTION tinyint(1)  not null comment 'рецепт',
    `GROUP`      varchar(30) not null comment 'группа'
)
    comment 'классификация аптечных товаров';

create table if not exists client
(
    ID           int unsigned auto_increment
        primary key,
    FULL_NAME    varchar(100) not null,
    PHONE_NUMBER varchar(11)  not null
)
    comment 'клиент';

create table if not exists corporation
(
    ID           int unsigned auto_increment
        primary key,
    NAME         varchar(100) not null,
    ACTUAL_OWNER varchar(50)  not null comment 'фактический собственник',
    CONTACTS     varchar(100) not null
);

create table if not exists manufacturer
(
    ID       int unsigned auto_increment
        primary key,
    NAME     varchar(100) not null comment 'название',
    CONTACTS varchar(150) not null comment 'контакты'
);

create table if not exists pharmacy
(
    NAME     varchar(50)  not null,
    ADDRESS  varchar(100) not null,
    CONTACTS varchar(150) not null,
    ID       int unsigned auto_increment
        primary key
)
    comment 'аптека';

create table if not exists product
(
    ID    int unsigned auto_increment
        primary key,
    NAME  varchar(70)  not null comment 'наименование',
    PRICE int unsigned not null comment 'стоимость'
)
    comment 'товар';

create table if not exists client_product
(
    ID         int unsigned auto_increment
        primary key,
    ID_CLIENT  int unsigned not null,
    ID_PRODUCT int unsigned not null,
    constraint FK_CLIENT
        foreign key (ID_CLIENT) references client (ID)
            on update cascade on delete cascade,
    constraint FK_PRODUCT
        foreign key (ID_PRODUCT) references product (ID)
            on update cascade on delete cascade
);

create table if not exists product_group
(
    ID         int unsigned auto_increment
        primary key,
    ID_GROUP   int unsigned not null comment 'FK группы',
    ID_PRODUCT int unsigned not null comment 'FK товара',
    constraint FK1_PRODUCT
        foreign key (ID_PRODUCT) references product (ID)
            on update cascade on delete cascade,
    constraint FK_GROUP
        foreign key (ID_GROUP) references classification_of_pharmacy_products (ID)
            on update cascade on delete cascade
)
    comment 'товар-группа';

create table if not exists provider
(
    ID       int unsigned auto_increment
        primary key,
    NAME     varchar(100) not null,
    CONTACTS varchar(150) not null
);

create table if not exists product_provider
(
    ID          int unsigned auto_increment
        primary key,
    ID_PRODUCT  int unsigned not null,
    ID_PROVIDER int unsigned not null,
    constraint FK1_PROVIDER
        foreign key (ID_PROVIDER) references provider (ID)
            on update cascade on delete cascade,
    constraint FK3_PRODUCT
        foreign key (ID_PRODUCT) references product (ID)
            on update cascade on delete cascade
)
    comment 'товар-поставщик';

create table if not exists provider_manufacturer
(
    ID              int unsigned auto_increment
        primary key,
    ID_PROVIDER     int unsigned not null,
    ID_MANUFACTURER int unsigned not null,
    constraint FK1_MANUFACTURER
        foreign key (ID_MANUFACTURER) references manufacturer (ID)
            on update cascade on delete cascade,
    constraint FK_PROVIDER
        foreign key (ID_PROVIDER) references provider (ID)
            on update cascade on delete cascade
)
    comment 'поставщик-произаодитель';

create table if not exists rating
(
    GRADE int unsigned not null comment 'оценка',
    ID    int unsigned auto_increment
        primary key
);

create table if not exists rating_client
(
    ID_RATING int unsigned not null,
    ID        int unsigned auto_increment
        primary key,
    ID_CLIENT int unsigned not null,
    constraint FK1_CLIENT
        foreign key (ID_CLIENT) references client (ID)
            on update cascade on delete cascade,
    constraint FK_RATING
        foreign key (ID_RATING) references rating (ID)
            on update cascade on delete cascade
)
    comment 'рейтинг-клиент';

create table if not exists worker
(
    ID          int unsigned auto_increment
        primary key,
    `FULL NAME` varchar(100) not null,
    POST        varchar(25)  not null
)
    comment 'сотрудник';

