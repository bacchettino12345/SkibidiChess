CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_verification_codes` ON SCHEDULE EVERY 1 SECOND STARTS '2025-04-27 18:36:17' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Cancella i codici di verifica scaduti' DO DELETE FROM verification_codes 
    WHERE expires < NOW()
