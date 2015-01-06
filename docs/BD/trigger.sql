DELIMITER $$

CREATE TRIGGER check_delete 
	after delete ON beep_evento_check for each row 
BEGIN
	DECLARE updatecount integer;
  SET updatecount = ( SELECT count(*) FROM beep_evento_check  WHERE eve_id = old.eve_id);
  IF updatecount>0
    THEN
      UPDATE beep_evento SET eve_check = updatecount WHERE eve_id = old.eve_id;
  END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER check_update 
	after insert ON beep_evento_check for each row 
BEGIN
	DECLARE updatecount integer;
  SET updatecount = ( SELECT count(*) FROM beep_evento_check  WHERE eve_id = new.eve_id);
  IF updatecount>0
    THEN
      UPDATE beep_evento SET eve_check = updatecount WHERE eve_id = new.eve_id;
  END IF;
END$$

DELIMITER ;