DROP TABLE IF EXISTS "save";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "save" (
  "id" int NOT NULL,
  "club" varchar(255) NOT NULL,
  "cours" varchar(255) NOT NULL,
  "jour" varchar(255) NOT NULL,
  "réservé" smallint NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "UNIQ_55663ADEBF396750" UNIQUE  ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "save"
--

LOCK TABLES "save" WRITE;
/*!40000 ALTER TABLE "save" DISABLE KEYS */;
INSERT INTO "save" VALUES (1,'Saint-Lazare','STEP','Dimanche',1);
/*!40000 ALTER TABLE "save" ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
