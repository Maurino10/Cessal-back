    INSERT INTO gender (name) VALUES
        ('Homme'),
        ('Femme');

    INSERT INTO post (name, role) VALUES
        ('Greffier', 'greffier'),
        ('Magistrat', 'magistrat'),
        ('Admin Local', 'admin_local'),
        ('Ministère', 'ministere');

    -- INSERTS pour la table profil (avec id explicite PR000001..PR000020)
    INSERT INTO profil (id, last_name, first_name, birthday, address, cin, immatriculation, email, id_gender) VALUES
    ('PR000001','Rakoto','Jean','1985-04-12','Antananarivo',198504120001,'IM001','jean.rakoto@example.com','GNR1'),
    ('PR000002','Rasoa','Marie','1990-07-25','Toamasina',199007250002,'IM002','marie.rasoa@example.com','GNR2'),
    ('PR000003','Randria','Hery','1982-11-03','Fianarantsoa',198211030003,'IM003','hery.randria@example.com','GNR1'),
    ('PR000004','Ravel','Lova','1995-03-18','Mahajanga',199503180004,'IM004','lova.ravel@example.com','GNR1'),
    ('PR000005','Rakotoarisoa','Fanja','1988-09-10','Antsiranana',198809100005,'IM005','fanja.rakotoarisoa@example.com','GNR2'),
    ('PR000006','Ranaivo','Solo','1983-01-20','Toliara',198301200006,'IM006','solo.ranaivo@example.com','GNR1'),
    ('PR000007','Rasolonjatovo','Hanta','1992-05-30','Morondava',199205300007,'IM007','hanta.rasolonjatovo@example.com','GNR2'),
    ('PR000008','Ramaroson','Tiana','1986-06-14','Ambositra',198606140008,'IM008','tiana.ramaroson@example.com','GNR1'),
    ('PR000009','Ratsimba','Lalao','1993-10-02','Ambatondrazaka',199310020009,'IM009','lalao.ratsimba@example.com','GNR2'),
    ('PR000010','Rakotondrazaka','Mamy','1979-12-27','Antsirabe',197912270010,'IM010','mamy.rakotondrazaka@example.com','GNR1'),
    ('PR000011','Andrianina','Voahirana','1996-08-09','Manakara',199608090011,'IM011','voahirana.andrianina@example.com','GNR2'),
    ('PR000012','Ravelojaona','Toky','1984-02-22','Sambava',198402220012,'IM012','toky.ravelojaona@example.com','GNR1'),
    ('PR000013','Rakotomanga','Sarobidy','1991-07-11','Nosy Be',199107110013,'IM013','sarobidy.rakotomanga@example.com','GNR2'),
    ('PR000014','Andriambelo','Haja','1980-09-17','Ambanja',198009170014,'IM014','haja.andriambelo@example.com','GNR1'),
    ('PR000015','Ravony','Malala','1987-03-04','Ambilobe',198703040015,'IM015','malala.ravony@example.com','GNR2'),
    ('PR000016','Razanakolona','Tahina','1994-12-19','Farafangana',199412190016,'IM016','tahina.razanakolona@example.com','GNR1'),
    ('PR000017','Rasoamanana','Soa','1989-06-28','Mananjary',198906280017,'IM017','soa.rasoamanana@example.com','GNR2'),
    ('PR000018','Andriatsitoha','Nomena','1997-01-07','Ambatolampy',199701070018,'IM018','nomena.andriatsitoha@example.com','GNR1'),
    ('PR000019','Razanamasy','Ketaka','1981-04-23','Antalaha',198104230019,'IM019','ketaka.razanamasy@example.com','GNR2'),
    ('PR000020','Ravololomanga','Hasina','1998-11-15','Miarinarivo',199811150020,'IM020','hasina.ravololomanga@example.com','GNR1');


    -- INSERTS pour la table users (password hashés, id_profil liés)
    INSERT INTO users (password, id_profil, id_tpi, id_post) VALUES
    ('$2b$12$zq6kD5Yk8QgqgGq2o8z0IuB1m8tYK0xvP1p9G6lNJiI9IY4n1zXba','PR000001','TPI0049','POST01'),
    ('$2b$12$FtQ1R2Wk/1p4k3oPCtNqJe1vZVq0O0R2XGm0IuY0s6wZ0q1bA6yQ','PR000002','TPI0050','POST02'),
    ('$2b$12$Xv6J8Q1aYv7Gk9hLz1sKle7Q9b3V/0P1oGQf5m2R8nKc1eJtUo1qW','PR000003','TPI0051','POST03'),
    ('$2b$12$K8mC2lQpWv5Yz9tFq3sNhO4rJx0Bv6uZc1sVe8bQ0mGj2pHn3rXy','PR000004','TPI0052','POST01'),
    ('$2b$12$J7nH3dPqZs6Vf8wLk2bUdR4oXv9Pq6sYc3tQe5rW8yNf1pHs2uVx','PR000005','TPI0053','POST02'),
    ('$2b$12$P/0XM0siqt.5pf77ePEP3ulAIbwctSYgSFTf3hNne.yvZHgqtQFIS','PR000006','TPI0054','POST03'),
    ('$2b$12$QHRqDLPVt.WTlM6KZ26E8exa9QetH4RCIMEsNMLqJoVQHGcSJV/ve','PR000007','TPI0055','POST01'),
    ('$2b$12$vlY42iVuewxBLjRu1rYBj.7gcoaEZ21ze8Z.rdarLsqyyGggoGHX2','PR000008','TPI0056','POST02'),
    ('$2b$12$4FdTuZ.BW8krZsOFH8M/sOzdMNDg1CL8jDAkqsWPg6ybgGEnJ7Tce','PR000009','TPI0057','POST03'),
    ('$2b$12$YnbHveJrjRywOVJgB6R8gOHJMgcz.wHJhBH2Uw6wIHvNhX07GLD4O','PR000010','TPI0058','POST01'),
    ('$2b$12$hIJmGhVTnegDvsIKx1iZqOCd5lCGvmJs69U45v3yfzbTdudJOJ8yK','PR000011','TPI0059','POST02'),
    ('$2b$12$amVRlzpqxtkyU3hqr19V9OoaXEWLHFp/msvwfVTiWVTZ8AGWSWW96','PR000012','TPI0060','POST03'),
    ('$2b$12$YQonkvhXqAx0OJLkqIsWre6dY1z3Sr4/geFk7k.MHdSaDpEy0E4PO','PR000013','TPI0061','POST01'),
    ('$2b$12$VuYldpNlz1qjeEE/gK.Nu.I2gG8whcgpfWVEOILOOqhGXdcqghwVO','PR000014','TPI0062','POST02'),
    ('$2b$12$NCIUS.An6xReR2414MYZXuTHYId0BzUEerpoOjv1eNtVM.1a/GTGu','PR000015','TPI0063','POST03'),
    ('$2b$12$9yaYD/DLz/CJhJjAPrZ/NuGRGkMLzJhF2/IHOGBsZfxtP2MQd/HbS','PR000016','TPI0064','POST01'),
    ('$2b$12$ZxaITVSSCu06IbkemeDGNeup9bRIrjNeY.VPd6AUFyroWEEFgOsMK','PR000017','TPI0065','POST02'),
    ('$2b$12$w5DePD6ekZxyc9SMnZZ2J.CjnOCwarzY.HqIOqCH1iEsohB5D7a.S','PR000018','TPI0066','POST03'),
    ('$2b$12$jhR.xsCp8aoUWPUD7e5QoeOxLZwsUw132OjteSpVzfl6yaPOoMOca','PR000019','TPI0067','POST01'),
    ('$2b$12$Wt4Kg0uQDpcEGgNhtTPsCOWmuS343.oWue5Jpa9YSMcCrUWgm7.Za','PR000020','TPI0068','POST02');
  



    INSERT INTO cession (numero_dossier, request_subject, reimbursed_amount, date_cession, id_user, id_tpi) VALUES
    ('DOS-2025-001', 'Demande de cession volontaire de salaire pour remboursement de prêt bancaire', 1500000.00, '2025-01-15', 'USR000004', 'TPI0060'),

    ('DOS-2025-002', 'Cession volontaire de salaire pour paiement de pension alimentaire', 800000.00, '2025-02-02', 'USR000004', 'TPI0060'),

    ('DOS-2025-003', 'Demande de cession pour remboursement d’un crédit scolaire', 500000.00, '2025-03-12', 'USR000005', 'TPI0060'),

    ('DOS-2025-004', 'Cession volontaire de salaire pour règlement de dettes personnelles', 1200000.00, '2025-04-20'),

    ('DOS-2025-005', 'Cession volontaire de salaire en faveur d’un organisme de microfinance', 950000.00, '2025-05-05');


    -- Pour cession 1
    INSERT INTO cession_plaintiff (last_name, first_name, address, cin, id_cession) VALUES
    ('Rakotomalala', 'Henintsoa', 'Ambatonakanga, Antananarivo', 201234567890, 1);

    -- Pour cession 2
    INSERT INTO cession_plaintiff (last_name, first_name, address, cin, id_cession) VALUES
    ('Randrianarisoa', 'Mamy', 'Ambohimanarina, Antananarivo', 201234567891, 2),
    ('Rasoanaivo', 'Clémentine', 'Andavamamba, Antananarivo', 201234567892, 2);

    -- Pour cession 3
    INSERT INTO cession_plaintiff (last_name, first_name, address, cin, id_cession) VALUES
    ('Andriamiharisoa', 'Haja', 'Antohomadinika, Antananarivo', 201234567893, 3);

    -- Pour cession 4
    INSERT INTO cession_plaintiff (last_name, first_name, address, cin, id_cession) VALUES
    ('Rakotoarivony', 'Tiana', 'Analamahitsy, Fianarantsoa', 201234567894, 4);

    -- Pour cession 5
    INSERT INTO cession_plaintiff (last_name, first_name, address, cin, id_cession) VALUES
    ('Rabenandrasana', 'Vololona', 'Andoharanofotsy, Fianarantsoa', 201234567895, 5),
    ('Raharimalala', 'Tojo', 'Itaosy, Fianarantsoa', 201234567896, 5);

------------------------------------------------------------------------------------------------------------------

    INSERT INTO cession_defendant (last_name, first_name, address, cin, salary_amount, remark, id_cession) VALUES
    ('Ratsimbazafy', 'Jean', '67Ha, Antananarivo', 301234567890, 1200000.00, 'Aucune observation', 1),
    ('Andrianantenaina', 'Soa', 'Anosibe, Antananarivo', 301234567891, 950000.00, NULL, 1);

    -- Pour cession 2
    INSERT INTO cession_defendant (last_name, first_name, address, cin, salary_amount, remark, id_cession) VALUES
    ('Rakotondrazaka', 'Michel', 'Ankorondrano, Antananarivo', 301234567892, 1500000.00, 'Revenu stable', 2);

    -- Pour cession 3
    INSERT INTO cession_defendant (last_name, first_name, address, cin, salary_amount, remark, id_cession) VALUES
    ('Ravelomanana', 'Hery', 'Ambatobe, Antananarivo', 301234567893, 800000.00, 'A déjà une autre cession en cours', 3),
    ('Ratsimandresy', 'Miora', 'Ivato, Antananarivo', 301234567894, 700000.00, NULL, 3),
    ('Rabefaniry', 'Tahina', 'Ambohimangakely, Antananarivo', 301234567895, 650000.00, 'Demande de révision possible', 3);

    -- Pour cession 4
    INSERT INTO cession_defendant (last_name, first_name, address, cin, salary_amount, remark, id_cession) VALUES
    ('Andriamanantena', 'Malala', 'Ankadifotsy, Fianarantsoa', 301234567896, 1100000.00, NULL, 4),
    ('Rabearivelo', 'Haja', 'Ambohitrimanjaka, Fianarantsoa', 301234567897, 900000.00, 'Souhaite un échéancier', 4);

    -- Pour cession 5
    INSERT INTO cession_defendant (last_name, first_name, address, cin, salary_amount, remark, id_cession) VALUES
    ('Rasamoely', 'Faniry', 'Anosiala, Antananarivo', 301234567898, 1000000.00, 'Charge familiale élevée', 5);



INSERT INTO cession_natural_person_address (id_cession_natural_person, address, date_address) VALUES (38, 'Lot IB 35/01 Ambatonakanga, Antananarivo', '2025-10-05'); 
INSERT INTO cession_natural_person_address (id_cession_natural_person, address, date_address) VALUES (39, 'Lot Bloc 32/02 67Ha, Antananarivo', '2025-10-05');        
INSERT INTO cession_natural_person_address (id_cession_natural_person, address, date_address) VALUES (40, 'Lot LV 46 Anosibe, Antananarivo', '2025-10-05');          
INSERT INTO cession_natural_person_address (id_cession_natural_person, address, date_address) VALUES (41, 'Lot DP 015 Ankorondrano, Antananarivo', '2025-10-05');    
INSERT INTO cession_natural_person_address (id_cession_natural_person, address, date_address) VALUES (42, 'Lot MT 01/A9 Analamahitsy', '2025-10-05');                  
