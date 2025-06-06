PGDMP      (                }            praukk    16.2    16.2 h    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    158469    praukk    DATABASE     }   CREATE DATABASE praukk WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_Indonesia.1252';
    DROP DATABASE praukk;
                postgres    false                        3079    166694 	   uuid-ossp 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;
    DROP EXTENSION "uuid-ossp";
                   false            �           0    0    EXTENSION "uuid-ossp"    COMMENT     W   COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';
                        false    2            �            1259    183740    detail_pembelians    TABLE     �   CREATE TABLE public.detail_pembelians (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    id_produk jsonb NOT NULL,
    qty jsonb NOT NULL,
    harga jsonb NOT NULL
);
 %   DROP TABLE public.detail_pembelians;
       public         heap    postgres    false            �            1259    183739    detail_pembelians_id_seq    SEQUENCE     �   CREATE SEQUENCE public.detail_pembelians_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.detail_pembelians_id_seq;
       public          postgres    false    240            �           0    0    detail_pembelians_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.detail_pembelians_id_seq OWNED BY public.detail_pembelians.id;
          public          postgres    false    239            �            1259    183630    failed_jobs    TABLE     &  CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
    DROP TABLE public.failed_jobs;
       public         heap    postgres    false            �            1259    183629    failed_jobs_id_seq    SEQUENCE     {   CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.failed_jobs_id_seq;
       public          postgres    false    223            �           0    0    failed_jobs_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;
          public          postgres    false    222            �            1259    183654    members    TABLE     �   CREATE TABLE public.members (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    no_hp character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    point integer
);
    DROP TABLE public.members;
       public         heap    postgres    false            �            1259    183653    members_id_seq    SEQUENCE     w   CREATE SEQUENCE public.members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.members_id_seq;
       public          postgres    false    227            �           0    0    members_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.members_id_seq OWNED BY public.members.id;
          public          postgres    false    226            �            1259    159079 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false            �            1259    159078    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    217            �           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    216            �            1259    183695    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false            �            1259    183706    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false            �            1259    183616    password_reset_tokens    TABLE     �   CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);
 )   DROP TABLE public.password_reset_tokens;
       public         heap    postgres    false            �            1259    183623    password_resets    TABLE     �   CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);
 #   DROP TABLE public.password_resets;
       public         heap    postgres    false            �            1259    183733 
   pembelians    TABLE     W  CREATE TABLE public.pembelians (
    id bigint NOT NULL,
    tanggal_penjualan timestamp(0) without time zone,
    total_harga numeric,
    total_bayar numeric,
    kembalian integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    id_detail_pembelian jsonb NOT NULL,
    id_member integer
);
    DROP TABLE public.pembelians;
       public         heap    postgres    false            �            1259    183732    pembelians_id_seq    SEQUENCE     z   CREATE SEQUENCE public.pembelians_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.pembelians_id_seq;
       public          postgres    false    238            �           0    0    pembelians_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.pembelians_id_seq OWNED BY public.pembelians.id;
          public          postgres    false    237            �            1259    183674    permissions    TABLE     �   CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false            �            1259    183673    permissions_id_seq    SEQUENCE     {   CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    231            �           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    230            �            1259    183642    personal_access_tokens    TABLE     �  CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE public.personal_access_tokens;
       public         heap    postgres    false            �            1259    183641    personal_access_tokens_id_seq    SEQUENCE     �   CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE public.personal_access_tokens_id_seq;
       public          postgres    false    225            �           0    0    personal_access_tokens_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;
          public          postgres    false    224            �            1259    183665    produks    TABLE     )  CREATE TABLE public.produks (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    stock integer NOT NULL,
    harga_beli integer NOT NULL,
    gambar character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.produks;
       public         heap    postgres    false            �            1259    183664    produks_id_seq    SEQUENCE     w   CREATE SEQUENCE public.produks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.produks_id_seq;
       public          postgres    false    229            �           0    0    produks_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.produks_id_seq OWNED BY public.produks.id;
          public          postgres    false    228            �            1259    183717    role_has_permissions    TABLE     m   CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);
 (   DROP TABLE public.role_has_permissions;
       public         heap    postgres    false            �            1259    183685    roles    TABLE     �   CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            �            1259    183684    roles_id_seq    SEQUENCE     u   CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    233            �           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    232            �            1259    183604    users    TABLE     �  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'kasir'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['kasir'::character varying, 'admin'::character varying])::text[])))
);
    DROP TABLE public.users;
       public         heap    postgres    false            �            1259    183603    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    219            �           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    218            �           2604    183743    detail_pembelians id    DEFAULT     |   ALTER TABLE ONLY public.detail_pembelians ALTER COLUMN id SET DEFAULT nextval('public.detail_pembelians_id_seq'::regclass);
 C   ALTER TABLE public.detail_pembelians ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    239    240    240            �           2604    183633    failed_jobs id    DEFAULT     p   ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);
 =   ALTER TABLE public.failed_jobs ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    222    223    223            �           2604    183657 
   members id    DEFAULT     h   ALTER TABLE ONLY public.members ALTER COLUMN id SET DEFAULT nextval('public.members_id_seq'::regclass);
 9   ALTER TABLE public.members ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    227    226    227            �           2604    159082    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    216    217    217            �           2604    183736    pembelians id    DEFAULT     n   ALTER TABLE ONLY public.pembelians ALTER COLUMN id SET DEFAULT nextval('public.pembelians_id_seq'::regclass);
 <   ALTER TABLE public.pembelians ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    237    238    238            �           2604    183677    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    231    230    231            �           2604    183645    personal_access_tokens id    DEFAULT     �   ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);
 H   ALTER TABLE public.personal_access_tokens ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    224    225    225            �           2604    183668 
   produks id    DEFAULT     h   ALTER TABLE ONLY public.produks ALTER COLUMN id SET DEFAULT nextval('public.produks_id_seq'::regclass);
 9   ALTER TABLE public.produks ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    228    229    229            �           2604    183688    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    233    232    233            �           2604    183607    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    219    218    219            �          0    183740    detail_pembelians 
   TABLE DATA           ^   COPY public.detail_pembelians (id, created_at, updated_at, id_produk, qty, harga) FROM stdin;
    public          postgres    false    240   �~       o          0    183630    failed_jobs 
   TABLE DATA           a   COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
    public          postgres    false    223   ~       s          0    183654    members 
   TABLE DATA           Q   COPY public.members (id, name, no_hp, created_at, updated_at, point) FROM stdin;
    public          postgres    false    227   �       i          0    159079 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    217   p�       z          0    183695    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    234   ��       {          0    183706    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    235   ȁ       l          0    183616    password_reset_tokens 
   TABLE DATA           I   COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
    public          postgres    false    220   �       m          0    183623    password_resets 
   TABLE DATA           C   COPY public.password_resets (email, token, created_at) FROM stdin;
    public          postgres    false    221   �       ~          0    183733 
   pembelians 
   TABLE DATA           �   COPY public.pembelians (id, tanggal_penjualan, total_harga, total_bayar, kembalian, created_at, updated_at, id_detail_pembelian, id_member) FROM stdin;
    public          postgres    false    238   �       w          0    183674    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    231   x�       q          0    183642    personal_access_tokens 
   TABLE DATA           �   COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
    public          postgres    false    225   ��       u          0    183665    produks 
   TABLE DATA           ^   COPY public.produks (id, name, stock, harga_beli, gambar, created_at, updated_at) FROM stdin;
    public          postgres    false    229   ��       |          0    183717    role_has_permissions 
   TABLE DATA           F   COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
    public          postgres    false    236   d�       y          0    183685    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    233   ��       k          0    183604    users 
   TABLE DATA           X   COPY public.users (id, name, email, password, role, created_at, updated_at) FROM stdin;
    public          postgres    false    219   ��       �           0    0    detail_pembelians_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.detail_pembelians_id_seq', 22, true);
          public          postgres    false    239            �           0    0    failed_jobs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);
          public          postgres    false    222            �           0    0    members_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.members_id_seq', 7, true);
          public          postgres    false    226            �           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 71, true);
          public          postgres    false    216            �           0    0    pembelians_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.pembelians_id_seq', 20, true);
          public          postgres    false    237            �           0    0    permissions_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);
          public          postgres    false    230            �           0    0    personal_access_tokens_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);
          public          postgres    false    224            �           0    0    produks_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.produks_id_seq', 4, true);
          public          postgres    false    228            �           0    0    roles_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.roles_id_seq', 1, false);
          public          postgres    false    232            �           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 4, true);
          public          postgres    false    218            �           2606    183745 (   detail_pembelians detail_pembelians_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.detail_pembelians
    ADD CONSTRAINT detail_pembelians_pkey PRIMARY KEY (id);
 R   ALTER TABLE ONLY public.detail_pembelians DROP CONSTRAINT detail_pembelians_pkey;
       public            postgres    false    240            �           2606    183638    failed_jobs failed_jobs_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
       public            postgres    false    223            �           2606    183640 #   failed_jobs failed_jobs_uuid_unique 
   CONSTRAINT     ^   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);
 M   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
       public            postgres    false    223            �           2606    183663    members members_no_hp_unique 
   CONSTRAINT     X   ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_no_hp_unique UNIQUE (no_hp);
 F   ALTER TABLE ONLY public.members DROP CONSTRAINT members_no_hp_unique;
       public            postgres    false    227            �           2606    183661    members members_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.members DROP CONSTRAINT members_pkey;
       public            postgres    false    227            �           2606    159084    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    217            �           2606    183705 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    234    234    234            �           2606    183716 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    235    235    235            �           2606    183622 0   password_reset_tokens password_reset_tokens_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);
 Z   ALTER TABLE ONLY public.password_reset_tokens DROP CONSTRAINT password_reset_tokens_pkey;
       public            postgres    false    220            �           2606    183738    pembelians pembelians_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.pembelians
    ADD CONSTRAINT pembelians_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.pembelians DROP CONSTRAINT pembelians_pkey;
       public            postgres    false    238            �           2606    183683 .   permissions permissions_name_guard_name_unique 
   CONSTRAINT     u   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);
 X   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_name_guard_name_unique;
       public            postgres    false    231    231            �           2606    183681    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    231            �           2606    183649 2   personal_access_tokens personal_access_tokens_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_pkey;
       public            postgres    false    225            �           2606    183652 :   personal_access_tokens personal_access_tokens_token_unique 
   CONSTRAINT     v   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);
 d   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_token_unique;
       public            postgres    false    225            �           2606    183672    produks produks_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.produks
    ADD CONSTRAINT produks_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.produks DROP CONSTRAINT produks_pkey;
       public            postgres    false    229            �           2606    183731 .   role_has_permissions role_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);
 X   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
       public            postgres    false    236    236            �           2606    183694 "   roles roles_name_guard_name_unique 
   CONSTRAINT     i   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);
 L   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_name_guard_name_unique;
       public            postgres    false    233    233            �           2606    183692    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    233            �           2606    183615    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    219            �           2606    183613    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    219            �           1259    183698 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    234    234            �           1259    183709 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    235    235            �           1259    183628    password_resets_email_index    INDEX     X   CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);
 /   DROP INDEX public.password_resets_email_index;
       public            postgres    false    221            �           1259    183650 8   personal_access_tokens_tokenable_type_tokenable_id_index    INDEX     �   CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);
 L   DROP INDEX public.personal_access_tokens_tokenable_type_tokenable_id_index;
       public            postgres    false    225    225            �           2606    183699 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    231    234    4804            �           2606    183710 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    235    233    4808            �           2606    183720 ?   role_has_permissions role_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 i   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
       public          postgres    false    231    4804    236            �           2606    183725 9   role_has_permissions role_has_permissions_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
       public          postgres    false    4808    233    236            �   �   x���K�0���5T��|��_�@Z�ڮ� ���B�7�	ʄ�#+.
�m�a�����=�$BD���Ϯ�������е�\YT�]]���U%;Wc�]�ͭ*�����LPV��%d�,
�8�,!#D��2�A)!K�\��?C����]�K�\ٳJYB�>����� ��]�����Nۘ���e���qӞ,!�; E�Mv�����6l_���������u��@ �      o      x������ � �      s   �   x�m�=�0Fg�\ 䟘o0����� C���
��(Q�|�_^>���y{ NB,$'0�1	Ħ��ϔ�C�sm˲�ڜô��x3#Fg�˘C�Y9�R��=wq��@B��2���E��~V��o�I���b����\��?�FR�np�o	��)~)2<�'4����h^GT��y��q���+G�SJ�e`S      i   +  x����n� ����i���d�J�p��/�U�m�r����?�θ"�	E�9GN��f��e����<-�l�P'�$�B����b�!I�����$ y��5I���]��4(�Lz�A��y�[)!ن�w�M(�$ !����`u�1���_A��eg��wo�Fj(yN�:1�Ϯ#g�]h�ov�Lq�̜�+��� �S_a��Ϲ�x���!L99?Of Ӷ����~#��#����Y�V��g�ޟqsw�e���X]r%U���{�ϏM���b:�ZC���u&I���o ���5~      z      x������ � �      {      x������ � �      l      x������ � �      m      x������ � �      ~   I  x���]n� ����@*�����?Gm�Y��JK��h<|6�DI.��<Pqȭ�R)$�>@@ɜK���4Uc^"�A�i���Z)��8��\��%f�Ϸ�~3�[���~O�A�i#�	}�=�)}s�!|B�~O�JoKD�Ht@��.����� �����e@�5�1O�������ӣw�_�����r[W���ed�������]��[�a?�O��(Ɨ5������ܭv�!��^��'+#Rӱ&y�����C�X���kg�
_�k�A�a�p5h����Ӛ�ƞ^��쮖B���x��N���{l��|���8W      w      x������ � �      q      x������ � �      u   �   x�u�A
�0�ur�,ۅ:3��z�B	MP���"�~��"��Y|>�1Z\���ٷ@�I�{�&�{s_�e6�%�-���0��?��2p��H;��`/���[Q[��/�Su^+:�R;�Fq�t�%-�'?�O�*��mW��w؁�:���k.��MLy      |      x������ � �      y      x������ � �      k   �   x���=�0 @��W�j�����Q�H#�>z���HU��_��7��ݫ g#��[ѝ���:*th�_y���S?�ҵi�{���5(K��?�H�
xoz��@��-\��%���҄���0�K��x����Q��ޡ�8d�+wt�Y*��P��a�D�6{tv�9�y�9���KCӴ'tOK�     