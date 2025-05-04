CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_name TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE EXTENSION IF NOT EXISTS pgcrypto;
INSERT INTO users (user_name, password)
VALUES 
    ('admin', crypt('admin', gen_salt('bf', 8)));
SELECT * FROM users;

	
-- Products and sales section -----------------
DROP TABLE products;
CREATE TABLE products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title TEXT NOT NULL,
    description TEXT,
    price NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    stock INTEGER NOT NULL CHECK (stock >= 0),
	active BOOLEAN NOT NULL DEFAULT TRUE
);

INSERT INTO products (title, description, price, stock, active)
VALUES
    ('Product Test 1', 'Description for Product Test 1', 100.00, 50, TRUE),
    ('Product Test 2', 'Description for Product Test 2', 150.00, 30, TRUE);
SELECT * FROM products;

DELETE FROM sales WHERE 1=1;
DROP TABLE sales;
CREATE TABLE sales (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
	user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO sales (created_at, user_id)
VALUES 
    (CURRENT_TIMESTAMP, '9ba0086b-4f61-4faf-a81d-233b34f9429b'),  
    (CURRENT_TIMESTAMP, '9ba0086b-4f61-4faf-a81d-233b34f9429b');
SELECT * FROM sales;

CREATE TABLE sale_products (
    sale_id UUID NOT NULL REFERENCES sales(id) ON DELETE CASCADE,
    product_id UUID NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL CHECK (quantity >= 0),
    PRIMARY KEY (sale_id, product_id)
);

INSERT INTO sale_products (sale_id, product_id, quantity)
VALUES 
    ((SELECT id FROM sales ORDER BY created_at LIMIT 1),  
     (SELECT id FROM products WHERE title = 'Product Test 1'),  
     3);  
SELECT * FROM sale_products;

INSERT INTO sale_products (sale_id, product_id, quantity)
VALUES 
    ((SELECT id FROM sales ORDER BY created_at LIMIT 1 OFFSET 1), 
     (SELECT id FROM products WHERE title = 'Product Test 2'),  
     5);
SELECT * FROM sale_products;
-- Products and sales section END -----------------
---------------------------------------------------


----------------- Cart section -----------------
DROP TABLE cart_products;
CREATE TABLE cart_products (
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    product_id UUID NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL CHECK (quantity >= 1),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, product_id)
);

INSERT INTO cart_products (user_id, product_id, quantity, created_at)
VALUES
    ('9ba0086b-4f61-4faf-a81d-233b34f9429b', -- Admin user ID
     (SELECT id FROM products WHERE title = 'Product Test 1'), -- Product Test 1 ID
     3, -- Quantity for Product Test 1
     CURRENT_TIMESTAMP), -- Timestamp for when the product is added to the cart

    ('9ba0086b-4f61-4faf-a81d-233b34f9429b', -- Admin user ID
     (SELECT id FROM products WHERE title = 'Product Test 2'), -- Product Test 2 ID
     5, -- Quantity for Product Test 2
     CURRENT_TIMESTAMP);
SELECT * FROM cart_products;
-- Cart section END -------------------------------
---------------------------------------------------



