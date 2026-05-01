CREATE TABLE slabs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slab_number INT NOT NULL UNIQUE,
  title VARCHAR(255) NULL,
  location_note VARCHAR(255) NULL,
  status ENUM('pending','processing','review','complete','blocked') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE slab_images (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slab_id BIGINT UNSIGNED NOT NULL,
  image_path VARCHAR(500) NOT NULL,
  face_label VARCHAR(50) NULL,
  region_label VARCHAR(100) NULL,
  quality_score DECIMAL(5,4) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (slab_id) REFERENCES slabs(id)
);

CREATE TABLE ai_runs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slab_id BIGINT UNSIGNED NOT NULL,
  image_id BIGINT UNSIGNED NULL,
  stage VARCHAR(80) NOT NULL,
  model VARCHAR(120) NOT NULL,
  prompt_version VARCHAR(80) NOT NULL,
  input_tokens BIGINT UNSIGNED DEFAULT 0,
  output_tokens BIGINT UNSIGNED DEFAULT 0,
  status ENUM('queued','running','succeeded','failed') NOT NULL DEFAULT 'queued',
  error_message TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  finished_at TIMESTAMP NULL,
  FOREIGN KEY (slab_id) REFERENCES slabs(id),
  FOREIGN KEY (image_id) REFERENCES slab_images(id)
);

CREATE TABLE text_versions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slab_id BIGINT UNSIGNED NOT NULL,
  ai_run_id BIGINT UNSIGNED NULL,
  kind ENUM('raw_ocr','restored','translation_en','translation_vi','translation_my','review_note') NOT NULL,
  content LONGTEXT NOT NULL,
  confidence DECIMAL(5,4) NULL,
  evidence_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (slab_id) REFERENCES slabs(id),
  FOREIGN KEY (ai_run_id) REFERENCES ai_runs(id)
);

CREATE TABLE glossary_terms (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  pali_term VARCHAR(255) NOT NULL,
  english_term VARCHAR(255) NULL,
  vietnamese_term VARCHAR(255) NULL,
  notes TEXT NULL,
  UNIQUE KEY uniq_pali_term (pali_term)
);
