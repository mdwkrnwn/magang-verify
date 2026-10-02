ALTER TABLE portofolio_teknologi
ADD CONSTRAINT fk_portofolio_teknologi_portofolio
FOREIGN KEY (portofolio_id)
REFERENCES portofolios(id)
ON DELETE CASCADE;