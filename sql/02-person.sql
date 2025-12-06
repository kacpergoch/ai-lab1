create table people
(
    id          integer not null
        constraint category_pk
            primary key autoincrement,
    name text not null,
    surname text not null,
    bio text not null,
    hobbies text not null
);