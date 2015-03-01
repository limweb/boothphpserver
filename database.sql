

CREATE TABLE [dbo].[appuser] (
[id] int NOT NULL IDENTITY(1,1) ,
[username] varchar(255) COLLATE Thai_CI_AS NULL ,
[password] varchar(255) COLLATE Thai_CI_AS NULL ,
[role] varchar(255) COLLATE Thai_CI_AS NULL ,
[created_at] datetime NULL ,
[updated_at] datetime NULL ,
[created_by] varchar(255) COLLATE Thai_CI_AS NULL ,
[updated_by] varchar(255) COLLATE Thai_CI_AS NULL ,
CONSTRAINT [PK__app_user__3213E83F3A978D17] PRIMARY KEY ([id]),
CONSTRAINT [UQ__app_user__F3DBC5723D73F9C2] UNIQUE ([username] ASC)
)
ON [PRIMARY]
GO


