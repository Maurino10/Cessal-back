-- ------------------------------------- Treatment Temp Tpi
    CREATE OR REPLACE FUNCTION treatment_temp_instance()
    RETURNS void AS $$
    BEGIN
        INSERT INTO province (name) 
            SELECT DISTINCT (province)
                province
            FROM temp_instance ORDER BY province;

        INSERT INTO region (name, id_province)
            SELECT DISTINCT ON (region, province)
                region, p.id
            FROM temp_instance as tt
            JOIN province as p ON LOWER(tt.province) = LOWER(p.name)  
            ORDER BY region;

        INSERT INTO ca (name, id_province)
            SELECT DISTINCT ON (structure_parente, province)
                structure_parente, p.id
            FROM temp_instance as tt
            JOIN province as p ON LOWER(tt.province) = LOWER(p.name)  
            WHERE structure_parente IS NOT NULL
            ORDER BY structure_parente;


        INSERT INTO district (name, id_region)
            SELECT DISTINCT ON (district, region)
                district, r.id
            FROM temp_instance as tt 
            JOIN province as p ON LOWER(tt.province) = LOWER(p.name)
            JOIN region as r ON LOWER(tt.region) = LOWER(r.name) 
            ORDER BY tt.district;

        INSERT INTO tpi (name, id_ca, id_district)
            SELECT DISTINCT ON (structure_parente, structure_fille, district, region, province)
                structure_fille, ca.id, d.id
            FROM temp_instance as tt
            JOIN province as p ON LOWER(tt.province) = LOWER(p.name)
            JOIN ca ON LOWER(p.id) = LOWER(ca.id_province)
            JOIN region as r ON LOWER(tt.region) = LOWER(r.name) 
            JOIN district as d ON LOWER(tt.district) = LOWER(d.name) 
            ORDER BY tt.structure_fille;

        TRUNCATE TABLE temp_instance; 
        ALTER SEQUENCE temp_instance_id_seq RESTART WITH 1;
    END;
    $$ LANGUAGE plpgsql;


-- ------------------------------------- Trigger User Inscription Approved 
        CREATE OR REPLACE FUNCTION user_inscription_approved_or_reject()
        RETURNS TRIGGER AS $$
        DECLARE
            profil_id VARCHAR;
        BEGIN
            -- Vérifier si le status est passé à 1 (accepté)
            IF NEW.status = 1 AND OLD.status != 1 THEN
                INSERT INTO profil (last_name, first_name, birthday, address, cin, immatriculation, email, id_gender)
                VALUES (NEW.last_name, NEW.first_name, NEW.birthday, NEW.address, NEW.cin, NEW.immatriculation, NEW.email, NEW.id_gender)
                RETURNING id INTO profil_id;

                INSERT INTO users (password, id_profil, id_post, id_tpi)
                VALUES (NEW.password, profil_id, NEW.id_post, NEW.id_tpi);

            END IF;

            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;


        CREATE OR REPLACE TRIGGER trigger_user_inscription_approved_or_reject
        AFTER UPDATE ON inscription
        FOR EACH ROW
        EXECUTE FUNCTION user_inscription_approved_or_reject();

-- -- ------------------------------------- DROP TABLE 

    -- DROP TABLE cession_provision CASCADE;
    -- DROP TABLE cession_reference CASCADE;
    -- DROP TABLE cession_justificatif CASCADE;
    -- DROP TABLE cession_borrower_quota CASCADE;
    -- DROP TABLE cession_borrower CASCADE;
    -- DROP TABLE cession_lender CASCADE;
    -- DROP TABLE cession_natural_person_address CASCADE;
    -- DROP TABLE cession_natural_person CASCADE;
    -- DROP TABLE cession_legal_person_address CASCADE;
    -- DROP TABLE cession_legal_person CASCADE;
    -- DROP TABLE cession_ordonnance CASCADE;
    -- DROP TABLE cession_magistrat CASCADE;
    -- DROP TABLE cession CASCADE;
    -- DROP TABLE users CASCADE;
    -- DROP TABLE profil CASCADE;
    -- DROP TABLE inscription CASCADE;
    -- DROP TABLE gender CASCADE;
    -- DROP TABLE post CASCADE;
    -- DROP TABLE tpi CASCADE;
    -- DROP TABLE district CASCADE;
    -- DROP TABLE region CASCADE;
    -- DROP TABLE ca CASCADE;
    -- DROP TABLE province CASCADE;

-- -- ------------------------------------- DROP SEQUENCE 
    -- DROP SEQUENCE ca_seq CASCADE;                        
    -- DROP SEQUENCE cession_seq CASCADE;                   
    -- DROP SEQUENCE district_seq CASCADE;                  
    -- DROP SEQUENCE gender_seq CASCADE;                    
    -- DROP SEQUENCE post_seq CASCADE;                      
    -- DROP SEQUENCE profil_seq CASCADE;                    
    -- DROP SEQUENCE province_seq CASCADE;                  
    -- DROP SEQUENCE region_seq CASCADE;                    
    -- DROP SEQUENCE temp_instance_id_seq CASCADE;               
    -- DROP SEQUENCE tpi_seq CASCADE;                       
    -- DROP SEQUENCE users_seq CASCADE;  

-- -- ------------------------------------- TRUNCATE TABLE 

    -- TRUNCATE TABLE cession_reference CASCADE;
    -- TRUNCATE TABLE cession_borrower_quota CASCADE;
    -- TRUNCATE TABLE cession_justificatif CASCADE;
    -- TRUNCATE TABLE cession_borrower CASCADE;
    -- TRUNCATE TABLE cession_lender CASCADE;
    -- TRUNCATE TABLE cession_natural_person CASCADE;
    -- TRUNCATE TABLE cession_legal_person CASCADE;
    -- TRUNCATE TABLE cession_ordonnance CASCADE;
    -- TRUNCATE TABLE cession_magistrat CASCADE;
    -- TRUNCATE TABLE cession CASCADE;
--     TRUNCATE TABLE users CASCADE;
--     TRUNCATE TABLE profil CASCADE;
--     TRUNCATE TABLE inscription CASCADE;
--     TRUNCATE TABLE gender CASCADE;
--     TRUNCATE TABLE post CASCADE;
--     TRUNCATE TABLE tpi CASCADE;
--     TRUNCATE TABLE district CASCADE;
--     TRUNCATE TABLE region CASCADE;
--     TRUNCATE TABLE ca CASCADE;
--     TRUNCATE TABLE province CASCADE;
 
-- -- ------------------------------------- ALTER SEQUENCE 
--     ALTER SEQUENCE ca_seq RESTART WITH 1;                        
--     ALTER SEQUENCE cession_seq RESTART WITH 1;                   
--     ALTER SEQUENCE district_seq RESTART WITH 1;                  
--     ALTER SEQUENCE gender_seq RESTART WITH 1;                    
--     ALTER SEQUENCE post_seq RESTART WITH 1;                      
--     ALTER SEQUENCE province_seq RESTART WITH 1;                  
--     ALTER SEQUENCE region_seq RESTART WITH 1;                    
--     ALTER SEQUENCE temp_instance_id_seq RESTART WITH 1;               
--     ALTER SEQUENCE tpi_seq RESTART WITH 1;                       
--     ALTER SEQUENCE profil_seq RESTART WITH 1;                    
    -- ALTER SEQUENCE users_seq RESTART WITH 1;      
    -- ALTER SEQUENCE inscription_id_seq RESTART WITH 1;               

    -- ALTER SEQUENCE cession_borrower_id_seq RESTART WITH 1;                
    -- ALTER SEQUENCE cession_borrower_quota_id_seq RESTART WITH 1;          
    -- ALTER SEQUENCE cession_id_seq RESTART WITH 1;                         
    -- ALTER SEQUENCE cession_legal_person_address_id_seq RESTART WITH 1;  
    -- ALTER SEQUENCE cession_legal_person_id_seq RESTART WITH 1;            
    -- ALTER SEQUENCE cession_lender_id_seq RESTART WITH 1;                  
    -- ALTER SEQUENCE cession_magistrat_id_seq RESTART WITH 1;               
    -- ALTER SEQUENCE cession_natural_person_address_id_seq RESTART WITH 1;  
    -- ALTER SEQUENCE cession_natural_person_id_seq RESTART WITH 1;          
    -- ALTER SEQUENCE cession_ordonnance_id_seq RESTART WITH 1;              
    -- ALTER SEQUENCE cession_id_seq RESTART WITH 1;                            
