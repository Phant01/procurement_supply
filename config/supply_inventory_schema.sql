-- ============================================================
--  PROCUREMENT & SUPPLY INVENTORY SYSTEM
--  Philippine Government Agency — COA-Compliant
--  SQL Schema (MySQL 8.0+)
--
--  Basis:
--    - GAM for NGAs (Government Accounting Manual for
--      National Government Agencies), COA
--    - COA Circular 2015-010 (Semi-Expendable Properties)
--    - DBM-COA Joint Circular on Capitalization Threshold
--    - RA 9184 IRR (Procurement Law) — PO as entry point only
--
--  Tables:
--    1.  item_categories       — supply/equipment classification
--    2.  suppliers             — supplier master list
--    3.  offices               — offices / departments
--    4.  personnel             — accountable officers
--    5.  items                 — item/supply master list
--    6.  stock_cards           — one card per item (COA-required)
--    7.  purchase_orders       — approved PO header
--    8.  po_items              — PO line items
--    9.  po_receipts           — delivery / IAR per PO
--    10. po_receipt_items      — items received per delivery
--    11. stock_card_entries    — every stock movement
--    12. ris                   — Requisition and Issue Slip
--    13. ris_items             — RIS line items
--    14. ics                   — Inventory Custodian Slip
--    15. par                   — Property Acknowledgement Receipt
--
--  Views:
--    v_current_stock           — live stock balances (RPCI basis)
--    v_stock_card_ledger       — full movement history per item
--    v_rsmi_base               — RSMI report base query
--    v_ics_registry            — ICS accountability list
--    v_par_registry            — PAR accountability list
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';

-- ============================================================
-- 1.  ITEM CATEGORIES
--     item_type drives which COA form is generated on issuance:
--       consumable       → RSMI entry only
--       semi_expendable  → RSMI + ICS
--       equipment        → RSMI + PAR (PPE Ledger Card downstream)
-- ============================================================
CREATE TABLE IF NOT EXISTS item_categories (
    category_id     INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    category_name   VARCHAR(100)     NOT NULL,
    item_type       ENUM(
                        'consumable',
                        'semi_expendable',
                        'equipment'
                    )                NOT NULL,
    description     TEXT,
    created_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (category_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Seed common categories
INSERT INTO item_categories (category_name, item_type, description) VALUES
('Office Supplies',              'consumable',      'Bond paper, pens, folders, staple wire, etc.'),
('Janitorial & Sanitation',      'consumable',      'Cleaning materials, disinfectants, trash bags'),
('Medical & First Aid Supplies', 'consumable',      'Consumable medical and first aid items'),
('Fuel & Lubricants',            'consumable',      'Gasoline, diesel, oil, lubricants'),
('Semi-Expendable IT Items',     'semi_expendable', 'IT peripherals below capitalization threshold'),
('Semi-Expendable Furniture',    'semi_expendable', 'Furniture & fixtures below capitalization threshold'),
('Semi-Expendable Equipment',    'semi_expendable', 'Other equipment below capitalization threshold'),
('IT Equipment (PPE)',           'equipment',       'Computers, servers, printers above threshold'),
('Office Furniture (PPE)',       'equipment',       'Furniture & fixtures above capitalization threshold'),
('Machinery & Equipment (PPE)',  'equipment',       'Heavy or specialized equipment above threshold');


-- ============================================================
-- 2.  SUPPLIERS
-- ============================================================
CREATE TABLE IF NOT EXISTS suppliers (
    supplier_id      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    supplier_name    VARCHAR(200)    NOT NULL,
    contact_person   VARCHAR(150),
    telephone        VARCHAR(50),
    mobile           VARCHAR(50),
    email            VARCHAR(150),
    address          TEXT,
    tin_no           VARCHAR(20)     COMMENT 'Tax Identification Number',
    philgeps_reg_no  VARCHAR(50)     COMMENT 'PhilGEPS registration number',
    is_active        TINYINT(1)      NOT NULL DEFAULT 1,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (supplier_id),
    INDEX idx_suppliers_name (supplier_name)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 3.  OFFICES / DEPARTMENTS
-- ============================================================
CREATE TABLE IF NOT EXISTS offices (
    office_id        INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    office_code      VARCHAR(20)     NOT NULL,
    office_name      VARCHAR(200)    NOT NULL,
    department       VARCHAR(200),
    head_of_office   VARCHAR(150),
    is_active        TINYINT(1)      NOT NULL DEFAULT 1,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (office_id),
    UNIQUE KEY uq_office_code (office_code)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 4.  PERSONNEL
--     Accountable officers for ICS (semi-expendable) and
--     PAR (equipment/PPE assignments)
-- ============================================================
CREATE TABLE IF NOT EXISTS personnel (
    personnel_id     INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    office_id        INT UNSIGNED    NOT NULL,
    employee_no      VARCHAR(30),
    full_name        VARCHAR(200)    NOT NULL,
    position         VARCHAR(150),
    is_active        TINYINT(1)      NOT NULL DEFAULT 1,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (personnel_id),
    UNIQUE KEY uq_employee_no (employee_no),
    INDEX idx_personnel_office (office_id),

    CONSTRAINT fk_personnel_office
        FOREIGN KEY (office_id) REFERENCES offices (office_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 5.  ITEMS  (Supply / Equipment Master List)
--
--     unit_cost    — moving average cost; updated on each receipt
--     uacs_code    — Unified Accounts Code Structure code
--                    e.g. 5-02-03-010-00 for Office Supplies Expense
--     reorder_point — triggers low-stock alert in the PHP layer
-- ============================================================
CREATE TABLE IF NOT EXISTS items (
    item_id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    category_id      INT UNSIGNED    NOT NULL,
    item_code        VARCHAR(50),
    item_name        VARCHAR(300)    NOT NULL,
    description      TEXT,
    unit_of_measure  VARCHAR(30)     NOT NULL
                     COMMENT 'e.g. piece, ream, box, bottle, unit',
    uacs_code        VARCHAR(30)     COMMENT 'Unified Accounts Code Structure',
    unit_cost        DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
    reorder_point    DECIMAL(10,2)   NOT NULL DEFAULT 0
                     COMMENT 'Minimum stock level before re-ordering',
    is_active        TINYINT(1)      NOT NULL DEFAULT 1,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (item_id),
    UNIQUE KEY uq_item_code (item_code),
    INDEX idx_items_category (category_id),
    FULLTEXT INDEX ftx_item_name (item_name),

    CONSTRAINT fk_items_category
        FOREIGN KEY (category_id) REFERENCES item_categories (category_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 6.  STOCK CARDS
--     One record per item — mirrors the physical Stock Card
--     prescribed in GAM for NGAs.
--     balance_qty is updated by triggers after every
--     stock_card_entries insert.
-- ============================================================
CREATE TABLE IF NOT EXISTS stock_cards (
    stock_card_id    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    item_id          INT UNSIGNED    NOT NULL,
    balance_qty      DECIMAL(15,4)   NOT NULL DEFAULT 0,
    last_updated     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (stock_card_id),
    UNIQUE KEY uq_sc_item (item_id),

    CONSTRAINT fk_stock_card_item
        FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 7.  PURCHASE ORDERS
-- ============================================================
CREATE TABLE IF NOT EXISTS purchase_orders (
    po_id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    supplier_id      INT UNSIGNED    NOT NULL,
    office_id        INT UNSIGNED    NOT NULL
                     COMMENT 'End-user / requesting office',
    po_number        VARCHAR(50)     NOT NULL,
    po_date          DATE            NOT NULL,
    delivery_date    DATE            COMMENT 'Expected delivery date',
    place_of_delivery VARCHAR(300),
    fund_source      VARCHAR(100)
                     COMMENT 'e.g. GAA, SEF, MOOE, Capital Outlay',
    mode_of_procurement VARCHAR(100)
                     COMMENT 'e.g. Shopping, Small Value Procurement',
    total_amount     DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
    status           ENUM(
                         'draft',
                         'approved',
                         'partially_received',
                         'fully_received',
                         'cancelled'
                     )               NOT NULL DEFAULT 'draft',
    approved_by      VARCHAR(150),
    approved_date    DATE,
    cancelled_reason VARCHAR(300),
    remarks          TEXT,
    created_by       VARCHAR(100),
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (po_id),
    UNIQUE KEY uq_po_number (po_number),
    INDEX idx_po_supplier   (supplier_id),
    INDEX idx_po_office     (office_id),
    INDEX idx_po_date       (po_date),
    INDEX idx_po_status     (status),

    CONSTRAINT fk_po_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers (supplier_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_po_office
        FOREIGN KEY (office_id) REFERENCES offices (office_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 8.  PO LINE ITEMS
-- ============================================================
CREATE TABLE IF NOT EXISTS po_items (
    po_item_id       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    po_id            INT UNSIGNED    NOT NULL,
    item_id          INT UNSIGNED    NOT NULL,
    unit_of_measure  VARCHAR(30)     NOT NULL,
    qty_ordered      DECIMAL(15,4)   NOT NULL,
    unit_price       DECIMAL(15,2)   NOT NULL,
    total_price      DECIMAL(15,2)   GENERATED ALWAYS AS
                         (qty_ordered * unit_price) STORED,
    qty_received     DECIMAL(15,4)   NOT NULL DEFAULT 0
                     COMMENT 'Cumulative qty received across all deliveries',
    remarks          VARCHAR(300),

    PRIMARY KEY (po_item_id),
    INDEX idx_po_items_po   (po_id),
    INDEX idx_po_items_item (item_id),

    CONSTRAINT fk_po_items_po
        FOREIGN KEY (po_id) REFERENCES purchase_orders (po_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_po_items_item
        FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_po_qty_ordered
        CHECK (qty_ordered > 0),
    CONSTRAINT chk_po_unit_price
        CHECK (unit_price >= 0)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 9.  PO RECEIPTS  (Inspection and Acceptance Report — IAR)
--     One row per delivery event against a PO.
--     Partial deliveries = multiple rows for the same po_id.
--     Saving this record triggers stock card receipt entries.
-- ============================================================
CREATE TABLE IF NOT EXISTS po_receipts (
    receipt_id       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    po_id            INT UNSIGNED    NOT NULL,
    iar_number       VARCHAR(50)     NOT NULL
                     COMMENT 'Inspection and Acceptance Report number',
    receipt_date     DATE            NOT NULL,
    delivery_ref     VARCHAR(100)
                     COMMENT 'Supplier Delivery Receipt or Invoice number',
    received_by      VARCHAR(150),
    inspected_by     VARCHAR(150),
    approved_by      VARCHAR(150),
    remarks          TEXT,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (receipt_id),
    UNIQUE KEY uq_iar_number (iar_number),
    INDEX idx_receipt_po   (po_id),
    INDEX idx_receipt_date (receipt_date),

    CONSTRAINT fk_receipt_po
        FOREIGN KEY (po_id) REFERENCES purchase_orders (po_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 10. PO RECEIPT LINE ITEMS
--     Actual quantities received per item per IAR.
--     Inserting here should trigger:
--       a) Update po_items.qty_received
--       b) Insert into stock_card_entries (RECEIPT)
--       c) Update stock_cards.balance_qty
-- ============================================================
CREATE TABLE IF NOT EXISTS po_receipt_items (
    receipt_item_id  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    receipt_id       INT UNSIGNED    NOT NULL,
    po_item_id       INT UNSIGNED    NOT NULL,
    qty_received     DECIMAL(15,4)   NOT NULL,
    unit_cost        DECIMAL(15,2)   NOT NULL,
    remarks          VARCHAR(300),

    PRIMARY KEY (receipt_item_id),
    INDEX idx_recv_items_receipt  (receipt_id),
    INDEX idx_recv_items_po_item  (po_item_id),

    CONSTRAINT fk_recv_items_receipt
        FOREIGN KEY (receipt_id) REFERENCES po_receipts (receipt_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_recv_items_po_item
        FOREIGN KEY (po_item_id) REFERENCES po_items (po_item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_recv_qty
        CHECK (qty_received > 0),
    CONSTRAINT chk_recv_cost
        CHECK (unit_cost >= 0)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 11. STOCK CARD ENTRIES
--     Every movement — receipts, issuances, returns,
--     and adjustments — is recorded here.
--
--     ref_type  | ref_id points to
--     ----------+---------------------------
--     RECEIPT   | po_receipt_items.receipt_item_id
--     ISSUANCE  | ris_items.ris_item_id
--     RETURN    | ris_items.ris_item_id
--     ADJUSTMENT| (manual; ref_id = 0)
--
--     balance is carried forward and recorded per entry
--     to allow point-in-time reconstruction of the stock card.
-- ============================================================
CREATE TABLE IF NOT EXISTS stock_card_entries (
    entry_id         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    stock_card_id    INT UNSIGNED    NOT NULL,
    txn_date         DATE            NOT NULL,
    ref_type         ENUM(
                         'RECEIPT',
                         'ISSUANCE',
                         'RETURN',
                         'ADJUSTMENT'
                     )               NOT NULL,
    ref_id           INT UNSIGNED    NOT NULL DEFAULT 0
                     COMMENT 'FK to po_receipt_items or ris_items depending on ref_type',
    ref_number       VARCHAR(100)    NOT NULL
                     COMMENT 'IAR no. or RIS no. for display on stock card',
    office_id        INT UNSIGNED
                     COMMENT 'Issuing/receiving office (NULL for adjustments)',
    qty_in           DECIMAL(15,4)   NOT NULL DEFAULT 0,
    qty_out          DECIMAL(15,4)   NOT NULL DEFAULT 0,
    unit_cost        DECIMAL(15,2)   NOT NULL DEFAULT 0,
    balance          DECIMAL(15,4)   NOT NULL
                     COMMENT 'Running balance after this transaction',
    remarks          VARCHAR(300),
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (entry_id),
    INDEX idx_sce_stock_card  (stock_card_id),
    INDEX idx_sce_txn_date    (txn_date),
    INDEX idx_sce_ref         (ref_type, ref_id),
    INDEX idx_sce_office      (office_id),

    CONSTRAINT fk_sce_stock_card
        FOREIGN KEY (stock_card_id) REFERENCES stock_cards (stock_card_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_sce_office
        FOREIGN KEY (office_id) REFERENCES offices (office_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_sce_movement
        CHECK (qty_in >= 0 AND qty_out >= 0 AND (qty_in > 0 OR qty_out > 0)),
    CONSTRAINT chk_sce_balance
        CHECK (balance >= 0)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 12. REQUISITION AND ISSUE SLIP (RIS)
--     Submitted by requesting offices; approved by the
--     Supply Officer/Property Custodian.
-- ============================================================
CREATE TABLE IF NOT EXISTS ris (
    ris_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    office_id        INT UNSIGNED    NOT NULL,
    ris_number       VARCHAR(50)     NOT NULL,
    ris_date         DATE            NOT NULL,
    purpose          VARCHAR(500),
    requested_by     VARCHAR(150),
    approved_by      VARCHAR(150),
    issued_by        VARCHAR(150),
    received_by      VARCHAR(150),
    status           ENUM(
                         'pending',
                         'approved',
                         'issued',
                         'cancelled'
                     )               NOT NULL DEFAULT 'pending',
    remarks          TEXT,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (ris_id),
    UNIQUE KEY uq_ris_number (ris_number),
    INDEX idx_ris_office (office_id),
    INDEX idx_ris_date   (ris_date),
    INDEX idx_ris_status (status),

    CONSTRAINT fk_ris_office
        FOREIGN KEY (office_id) REFERENCES offices (office_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 13. RIS LINE ITEMS
--     When issued, inserting/updating here should trigger:
--       a) Insert into stock_card_entries (ISSUANCE)
--       b) Update stock_cards.balance_qty
--       c) If item_type = semi_expendable → create ICS record
--       d) If item_type = equipment       → create PAR record
-- ============================================================
CREATE TABLE IF NOT EXISTS ris_items (
    ris_item_id      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ris_id           INT UNSIGNED    NOT NULL,
    item_id          INT UNSIGNED    NOT NULL,
    qty_requested    DECIMAL(15,4)   NOT NULL,
    qty_issued       DECIMAL(15,4)   NOT NULL DEFAULT 0,
    unit_cost        DECIMAL(15,2)   NOT NULL DEFAULT 0,
    remarks          VARCHAR(300),

    PRIMARY KEY (ris_item_id),
    INDEX idx_ris_items_ris  (ris_id),
    INDEX idx_ris_items_item (item_id),

    CONSTRAINT fk_ris_items_ris
        FOREIGN KEY (ris_id) REFERENCES ris (ris_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_ris_items_item
        FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_ris_qty_req
        CHECK (qty_requested > 0),
    CONSTRAINT chk_ris_qty_issued
        CHECK (qty_issued >= 0)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 14. INVENTORY CUSTODIAN SLIP (ICS)
--     Auto-generated when a semi-expendable item is issued
--     via RIS. Accountable to an individual personnel.
--     GAM for NGAs, Volume II, Chapter 10.
-- ============================================================
CREATE TABLE IF NOT EXISTS ics (
    ics_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ris_item_id      INT UNSIGNED    NOT NULL,
    personnel_id     INT UNSIGNED    NOT NULL,
    ics_number       VARCHAR(50)     NOT NULL,
    ics_date         DATE            NOT NULL,
    quantity         DECIMAL(15,4)   NOT NULL DEFAULT 1,
    unit_cost        DECIMAL(15,2)   NOT NULL,
    total_cost       DECIMAL(15,2)   GENERATED ALWAYS AS
                         (quantity * unit_cost) STORED,
    estimated_life   VARCHAR(50)
                     COMMENT 'Useful life, e.g. 5 years',
    property_no      VARCHAR(100)
                     COMMENT 'Assigned property / inventory number',
    location         VARCHAR(200),
    status           ENUM(
                         'active',
                         'returned',
                         'written_off'
                     )               NOT NULL DEFAULT 'active',
    returned_date    DATE,
    remarks          TEXT,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (ics_id),
    UNIQUE KEY uq_ics_number (ics_number),
    INDEX idx_ics_ris_item   (ris_item_id),
    INDEX idx_ics_personnel  (personnel_id),
    INDEX idx_ics_status     (status),

    CONSTRAINT fk_ics_ris_item
        FOREIGN KEY (ris_item_id) REFERENCES ris_items (ris_item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_ics_personnel
        FOREIGN KEY (personnel_id) REFERENCES personnel (personnel_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


-- ============================================================
-- 15. PROPERTY ACKNOWLEDGEMENT RECEIPT (PAR)
--     Auto-generated when equipment/PPE is assigned to a
--     personnel via RIS. Triggers PPE Ledger Card entry.
--     GAM for NGAs, Volume II, Chapter 10.
-- ============================================================
CREATE TABLE IF NOT EXISTS par (
    par_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ris_item_id      INT UNSIGNED    NOT NULL,
    personnel_id     INT UNSIGNED    NOT NULL,
    par_number       VARCHAR(50)     NOT NULL,
    par_date         DATE            NOT NULL,
    quantity         DECIMAL(15,4)   NOT NULL DEFAULT 1,
    unit_cost        DECIMAL(15,2)   NOT NULL,
    total_cost       DECIMAL(15,2)   GENERATED ALWAYS AS
                         (quantity * unit_cost) STORED,
    property_no      VARCHAR(100)
                     COMMENT 'Assigned property number',
    serial_no        VARCHAR(100)
                     COMMENT 'Manufacturer serial number',
    brand_model      VARCHAR(200),
    location         VARCHAR(200),
    status           ENUM(
                         'active',
                         'transferred',
                         'returned',
                         'disposed'
                     )               NOT NULL DEFAULT 'active',
    transfer_date    DATE,
    remarks          TEXT,
    created_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (par_id),
    UNIQUE KEY uq_par_number (par_number),
    INDEX idx_par_ris_item  (ris_item_id),
    INDEX idx_par_personnel (personnel_id),
    INDEX idx_par_status    (status),

    CONSTRAINT fk_par_ris_item
        FOREIGN KEY (ris_item_id) REFERENCES ris_items (ris_item_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_par_personnel
        FOREIGN KEY (personnel_id) REFERENCES personnel (personnel_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
-- TRIGGERS
-- ============================================================

DELIMITER $$

-- After a delivery line item is inserted,
-- write the RECEIPT entry to the stock card and update balance.
CREATE TRIGGER trg_after_recv_item_insert
AFTER INSERT ON po_receipt_items
FOR EACH ROW
BEGIN
    DECLARE v_item_id      INT UNSIGNED;
    DECLARE v_sc_id        INT UNSIGNED;
    DECLARE v_new_balance  DECIMAL(15,4);
    DECLARE v_iar_number   VARCHAR(50);
    DECLARE v_receipt_date DATE;
    DECLARE v_po_id        INT UNSIGNED;

    -- Resolve item and stock card
    SELECT pi.item_id
      INTO v_item_id
      FROM po_items pi
     WHERE pi.po_item_id = NEW.po_item_id;

    SELECT sc.stock_card_id
      INTO v_sc_id
      FROM stock_cards sc
     WHERE sc.item_id = v_item_id;

    -- Get IAR number and date from parent receipt
    SELECT pr.iar_number, pr.receipt_date, pr.po_id
      INTO v_iar_number, v_receipt_date, v_po_id
      FROM po_receipts pr
     WHERE pr.receipt_id = NEW.receipt_id;

    -- Calculate new balance
    SET v_new_balance = (
        SELECT balance_qty FROM stock_cards WHERE stock_card_id = v_sc_id
    ) + NEW.qty_received;

    -- Write stock card entry
    INSERT INTO stock_card_entries
        (stock_card_id, txn_date, ref_type, ref_id,
         ref_number, qty_in, qty_out, unit_cost, balance)
    VALUES
        (v_sc_id, v_receipt_date, 'RECEIPT', NEW.receipt_item_id,
         v_iar_number, NEW.qty_received, 0, NEW.unit_cost, v_new_balance);

    -- Update running balance on stock card
    UPDATE stock_cards
       SET balance_qty = v_new_balance
     WHERE stock_card_id = v_sc_id;

    -- Update cumulative qty_received on the PO line item
    UPDATE po_items
       SET qty_received = qty_received + NEW.qty_received
     WHERE po_item_id = NEW.po_item_id;

    -- Update items.unit_cost (moving average)
    UPDATE items
       SET unit_cost = NEW.unit_cost
     WHERE item_id = v_item_id;
END$$


-- After RIS item qty_issued is set (status = issued),
-- write the ISSUANCE entry to the stock card and update balance.
CREATE TRIGGER trg_after_ris_item_update
AFTER UPDATE ON ris_items
FOR EACH ROW
BEGIN
    DECLARE v_sc_id       INT UNSIGNED;
    DECLARE v_new_balance DECIMAL(15,4);
    DECLARE v_ris_number  VARCHAR(50);
    DECLARE v_ris_date    DATE;
    DECLARE v_office_id   INT UNSIGNED;

    -- Only act when qty_issued changes from 0 to a positive value
    IF NEW.qty_issued > 0 AND OLD.qty_issued = 0 THEN

        SELECT sc.stock_card_id
          INTO v_sc_id
          FROM stock_cards sc
         WHERE sc.item_id = NEW.item_id;

        SELECT r.ris_number, r.ris_date, r.office_id
          INTO v_ris_number, v_ris_date, v_office_id
          FROM ris r
         WHERE r.ris_id = NEW.ris_id;

        SET v_new_balance = (
            SELECT balance_qty FROM stock_cards WHERE stock_card_id = v_sc_id
        ) - NEW.qty_issued;

        INSERT INTO stock_card_entries
            (stock_card_id, txn_date, ref_type, ref_id,
             ref_number, office_id, qty_in, qty_out, unit_cost, balance)
        VALUES
            (v_sc_id, v_ris_date, 'ISSUANCE', NEW.ris_item_id,
             v_ris_number, v_office_id, 0, NEW.qty_issued, NEW.unit_cost, v_new_balance);

        UPDATE stock_cards
           SET balance_qty = v_new_balance
         WHERE stock_card_id = v_sc_id;

    END IF;
END$$

DELIMITER ;


-- ============================================================
-- VIEWS — COA Report Helpers
-- ============================================================

-- V1: Current Stock Balance
--     Basis for the RPCI (Report on Physical Count of Inventories).
--     Run as: SELECT * FROM v_current_stock ORDER BY item_name;
CREATE OR REPLACE VIEW v_current_stock AS
SELECT
    sc.stock_card_id,
    i.item_id,
    i.item_code,
    i.item_name,
    i.unit_of_measure,
    ic.category_name,
    ic.item_type,
    i.uacs_code,
    sc.balance_qty,
    i.unit_cost,
    ROUND(sc.balance_qty * i.unit_cost, 2) AS total_value,
    sc.last_updated
FROM       stock_cards     sc
INNER JOIN items           i  ON i.item_id      = sc.item_id
INNER JOIN item_categories ic ON ic.category_id = i.category_id
WHERE  i.is_active = 1;


-- V2: Full Stock Card Ledger (per item movement history)
--     Filter by item: WHERE item_id = ?
--     Filter by period: WHERE txn_date BETWEEN ? AND ?
CREATE OR REPLACE VIEW v_stock_card_ledger AS
SELECT
    sce.entry_id,
    i.item_id,
    i.item_code,
    i.item_name,
    i.unit_of_measure,
    sce.txn_date,
    sce.ref_type,
    sce.ref_number,
    o.office_name,
    sce.qty_in,
    sce.qty_out,
    sce.unit_cost,
    ROUND(sce.qty_in  * sce.unit_cost, 2) AS amount_in,
    ROUND(sce.qty_out * sce.unit_cost, 2) AS amount_out,
    sce.balance,
    sce.remarks
FROM       stock_card_entries sce
INNER JOIN stock_cards        sc ON sc.stock_card_id = sce.stock_card_id
INNER JOIN items              i  ON i.item_id        = sc.item_id
LEFT  JOIN offices            o  ON o.office_id      = sce.office_id
ORDER BY   sc.item_id, sce.txn_date, sce.entry_id;


-- V3: RSMI Base View
--     Report of Supplies and Materials Issued.
--     Generated monthly. Filter by period in PHP:
--       WHERE period = '2025-01'  (YYYY-MM)
--     or: WHERE txn_date BETWEEN '2025-01-01' AND '2025-01-31'
CREATE OR REPLACE VIEW v_rsmi_base AS
SELECT
    DATE_FORMAT(sce.txn_date, '%Y-%m') AS period,
    sce.txn_date,
    r.ris_id,
    r.ris_number,
    r.ris_date,
    r.purpose,
    o.office_id,
    o.office_name,
    o.department,
    i.item_id,
    i.item_code,
    i.item_name,
    i.unit_of_measure,
    i.uacs_code,
    ic.item_type,
    ri.qty_issued,
    sce.unit_cost,
    ROUND(ri.qty_issued * sce.unit_cost, 2) AS total_cost
FROM       stock_card_entries sce
INNER JOIN stock_cards        sc ON sc.stock_card_id  = sce.stock_card_id
INNER JOIN items              i  ON i.item_id         = sc.item_id
INNER JOIN item_categories    ic ON ic.category_id    = i.category_id
INNER JOIN ris_items          ri ON ri.ris_item_id    = sce.ref_id
                                AND sce.ref_type      = 'ISSUANCE'
INNER JOIN ris                r  ON r.ris_id          = ri.ris_id
INNER JOIN offices            o  ON o.office_id       = r.office_id;


-- V4: ICS Accountability Registry
--     All active Inventory Custodian Slips.
CREATE OR REPLACE VIEW v_ics_registry AS
SELECT
    ics.ics_id,
    ics.ics_number,
    ics.ics_date,
    ics.property_no,
    i.item_id,
    i.item_name,
    i.unit_of_measure,
    ic.category_name,
    ics.quantity,
    ics.unit_cost,
    ics.total_cost,
    ics.estimated_life,
    ics.location,
    p.full_name   AS assigned_to,
    p.position,
    o.office_name,
    ics.status,
    ics.returned_date,
    ics.remarks
FROM       ics
INNER JOIN ris_items          ri ON ri.ris_item_id  = ics.ris_item_id
INNER JOIN items              i  ON i.item_id       = ri.item_id
INNER JOIN item_categories    ic ON ic.category_id  = i.category_id
INNER JOIN personnel          p  ON p.personnel_id  = ics.personnel_id
INNER JOIN offices            o  ON o.office_id     = p.office_id;


-- V5: PAR Accountability Registry
--     All Property Acknowledgement Receipts.
CREATE OR REPLACE VIEW v_par_registry AS
SELECT
    par.par_id,
    par.par_number,
    par.par_date,
    par.property_no,
    par.serial_no,
    par.brand_model,
    i.item_id,
    i.item_name,
    i.unit_of_measure,
    ic.category_name,
    par.quantity,
    par.unit_cost,
    par.total_cost,
    par.location,
    p.full_name   AS assigned_to,
    p.position,
    o.office_name,
    par.status,
    par.transfer_date,
    par.remarks
FROM       par
INNER JOIN ris_items          ri ON ri.ris_item_id  = par.ris_item_id
INNER JOIN items              i  ON i.item_id       = ri.item_id
INNER JOIN item_categories    ic ON ic.category_id  = i.category_id
INNER JOIN personnel          p  ON p.personnel_id  = par.personnel_id
INNER JOIN offices            o  ON o.office_id     = p.office_id;


-- ============================================================
-- SAMPLE QUERIES (Reference)
-- ============================================================

-- Pull stock card for a specific item in a period:
-- SELECT * FROM v_stock_card_ledger
--  WHERE item_id = 1
--    AND txn_date BETWEEN '2025-01-01' AND '2025-03-31';

-- Generate RSMI for January 2025:
-- SELECT * FROM v_rsmi_base WHERE period = '2025-01' ORDER BY office_name, item_name;

-- Check items below reorder point:
-- SELECT v.*, i.reorder_point
--   FROM v_current_stock v
--   JOIN items i ON i.item_id = v.item_id
--  WHERE v.balance_qty <= i.reorder_point;

-- List all active ICS for an office:
-- SELECT * FROM v_ics_registry
--  WHERE office_name = 'Finance Division' AND status = 'active';

-- List all active PAR:
-- SELECT * FROM v_par_registry WHERE status = 'active' ORDER BY assigned_to;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
