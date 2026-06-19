CREATE TABLE IF NOT EXISTS articles (
    id VARCHAR(5) PRIMARY KEY,
    title TEXT,
    content TEXT,
    post_at BIGINT,
    has_images BOOLEAN
)