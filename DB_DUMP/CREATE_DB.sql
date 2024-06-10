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
    `GROUP`      varchar(50) not null comment 'группа'
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
    NAME         varchar(70)  not null,
    ACTUAL_OWNER varchar(50)  not null comment 'фактический собственник',
    CONTACTS     varchar(100) not null
);

create table if not exists logistics
(
    ID       int unsigned auto_increment
        primary key,
    NAME     varchar(100) not null,
    CONTACTS varchar(150) not null
);

create table if not exists manufacturer
(
    ID       int unsigned auto_increment
        primary key,
    NAME     varchar(100) not null comment 'название',
    CONTACTS varchar(150) not null comment 'контакты'
);

create table if not exists logistics_manufacturer
(
    ID              int unsigned auto_increment
        primary key,
    ID_LOGISTICS    int unsigned not null,
    ID_MANUFACTURER int unsigned not null,
    constraint FK1_MANUFACTURER
        foreign key (ID_MANUFACTURER) references manufacturer (ID)
            on update cascade on delete cascade,
    constraint FK_LOGISTICS
        foreign key (ID_LOGISTICS) references logistics (ID)
            on update cascade on delete cascade
)
    comment 'поставщик-произаодитель';

create table if not exists pharmacy
(
    ID              int unsigned auto_increment
        primary key,
    NAME            varchar(50)  not null,
    ADDRESS         varchar(100) not null,
    CONTACTS        varchar(150) not null,
    CORPORTATION_FK int unsigned null comment 'Код организации',
    CITY_FK         int unsigned null,
    constraint CITY_FK
        foreign key (CITY_FK) references city (ID)
            on update cascade on delete cascade,
    constraint CORPORATION_FK
        foreign key (CORPORTATION_FK) references corporation (ID)
            on update cascade on delete cascade
)
    comment 'аптека';

create table if not exists employee
(
    ID          int unsigned auto_increment
        primary key,
    `FULL NAME` varchar(100) not null,
    POST        varchar(25)  not null,
    PHARMACY_FK int unsigned null,
    constraint PHARMACY_FK
        foreign key (PHARMACY_FK) references pharmacy (ID)
            on update cascade on delete cascade
)
    comment 'сотрудник';

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


create table if not exists product_logistics
(
    ID           int unsigned auto_increment
        primary key,
    ID_PRODUCT   int unsigned not null,
    ID_LOGISTICS int unsigned not null,
    constraint FK1_LOGISTICS
        foreign key (ID_LOGISTICS) references logistics (ID)
            on update cascade on delete cascade,
    constraint FK3_PRODUCT
        foreign key (ID_PRODUCT) references product (ID)
            on update cascade on delete cascade
)
    comment 'товар-поставщик';

create table if not exists rating
(
    ID          int unsigned auto_increment
        primary key,
    GRADE       tinyint unsigned not null comment 'оценка',
    ID_PHARMACY int unsigned     null,
    constraint FK1_PHARMACY
        foreign key (ID_PHARMACY) references pharmacy (ID)
            on update cascade on delete cascade
);

create table if not exists rating_client
(
    ID        int unsigned auto_increment
        primary key,
    ID_RATING int unsigned not null,
    ID_CLIENT int unsigned not null,
    constraint FK1_CLIENT
        foreign key (ID_CLIENT) references client (ID)
            on update cascade on delete cascade,
    constraint FK_RATING
        foreign key (ID_RATING) references rating (ID)
            on update cascade on delete cascade
)
    comment 'рейтинг-клиент';

