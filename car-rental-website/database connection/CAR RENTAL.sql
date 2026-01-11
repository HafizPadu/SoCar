CREATE TABLE CUSTOMER (
    CustID       NUMBER PRIMARY KEY,
    CustName     VARCHAR2(100),
    CustIC       VARCHAR2(20),
    CustPhoneNo  VARCHAR2(20),
    DOB           DATE,
    CustAge      NUMBER,
    CustEmail    VARCHAR2(100)
);

CREATE TABLE STAFF (
    StaffID       NUMBER PRIMARY KEY,
    StaffName     VARCHAR2(100),
    StaffIC       VARCHAR2(20),
    StaffPhoneNo  VARCHAR2(20),
    StaffPosition VARCHAR2(50),
    StaffSalary   NUMBER(8,2),
    StaffEmail    VARCHAR2(100),
    SupervisorID  NUMBER,
    
    CONSTRAINT fk_supervisor
        FOREIGN KEY (SupervisorID)
        REFERENCES STAFF(StaffID)
);

CREATE TABLE CAR (
    CarID        NUMBER PRIMARY KEY,
    CarModel     VARCHAR2(50),
    CarType      VARCHAR2(30),
    Availability VARCHAR2(20),
    PricePerDay  NUMBER(8,2)
);

CREATE TABLE PETROLEUM_CAR (
    CarID        NUMBER PRIMARY KEY,
    MaxRangeKM   NUMBER,
    FuelType     VARCHAR2(30),
    FuelCapacity NUMBER,

    CONSTRAINT fk_petrol_car
        FOREIGN KEY (CarID) REFERENCES CAR(CarID)
);

CREATE TABLE EV_CAR (
    CarID           NUMBER PRIMARY KEY,
    MaxRangeKM      NUMBER,
    BatteryCapacity NUMBER,
    ChargingTime    NUMBER,

    CONSTRAINT fk_ev_car
        FOREIGN KEY (CarID) REFERENCES CAR(CarID)
);

CREATE TABLE BOOKING (
    BookingID     NUMBER PRIMARY KEY,
    StartDate     DATE,
    EndDate       DATE,
    PickupMethod  VARCHAR2(50),
    Address       VARCHAR2(100),

    CustID  NUMBER,
    CarID   NUMBER,
    StaffID NUMBER,

    CONSTRAINT fk_booking_customer FOREIGN KEY (CustID)
        REFERENCES CUSTOMER(CustID),

    CONSTRAINT fk_booking_car FOREIGN KEY (CarID)
        REFERENCES CAR(CarID),

    CONSTRAINT fk_booking_staff FOREIGN KEY (StaffID)
        REFERENCES STAFF(StaffID)
);

CREATE TABLE CUSTOMER_BOOKING (
    CustID     NUMBER,
    BookingID  NUMBER,

    PRIMARY KEY (CustID, BookingID),

    CONSTRAINT fk_cb_customer FOREIGN KEY (CustID)
        REFERENCES CUSTOMER(CustID),

    CONSTRAINT fk_cb_booking FOREIGN KEY (BookingID)
        REFERENCES BOOKING(BookingID)
);

CREATE TABLE PAYMENT (
    PaymentID     NUMBER PRIMARY KEY,
    Amount        NUMBER(8,2),
    PaymentType   VARCHAR2(50),
    PaymentDate   DATE,
    PaymentMethod VARCHAR2(50),
    PaymentStatus VARCHAR2(30),

    BookingID NUMBER,

    CONSTRAINT fk_payment_booking FOREIGN KEY (BookingID)
        REFERENCES BOOKING(BookingID)
);

CREATE TABLE MAINTENANCE (
    MaintenanceID   NUMBER PRIMARY KEY,
    DatePerformed   DATE,
    MaintenanceType VARCHAR2(50),
    MaintenanceDetails VARCHAR2(200),
    Price           NUMBER(8,2),
    NextMaintenance DATE,

    StaffID NUMBER,
    CarID   NUMBER,

    CONSTRAINT fk_maint_staff FOREIGN KEY (StaffID)
        REFERENCES STAFF(StaffID),

    CONSTRAINT fk_maint_car FOREIGN KEY (CarID)
        REFERENCES CAR(CarID)
);

