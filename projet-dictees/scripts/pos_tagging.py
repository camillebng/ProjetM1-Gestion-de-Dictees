import spacy
import mysql.connector


nlp = spacy.load("fr_core_news_lg")


try:
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",       
        password="",      
        database="gr4m1IDL",
        charset="utf8mb4"
    )
    mycursor = mydb.cursor(dictionary=True)
    print("Connexion réussie.")


    mycursor.execute("SELECT id_dict, contenu_prof FROM version_prof")
    dictees = mycursor.fetchall()

    for dictee in dictees:
        id_d = dictee['id_dict']
        texte_prof = dictee['contenu_prof']
        

        doc = nlp(texte_prof)
        
        tokens_spacy = [t for t in doc if not t.is_space and not t.is_punct]
        
        query_tokens = "SELECT id_toks_eleve FROM toks_eleve WHERE id_dict_fk = %s ORDER BY position_eleve ASC"
        mycursor.execute(query_tokens, (id_d,))
        tokens_eleve_db = mycursor.fetchall()
        
        updates = []

        for t_spacy, t_db in zip(tokens_spacy, tokens_eleve_db):
            updates.append((
                t_spacy.pos_,          
                t_db['id_toks_eleve']  
            ))
        
        if updates:
            sql_update = "UPDATE toks_eleve SET pos_tok = %s WHERE id_toks_eleve = %s"
            mycursor.executemany(sql_update, updates)

    mydb.commit()

except mysql.connector.Error as err:
    print(f"Erreur : {err}")

finally:
    if 'mydb' in locals() and mydb.is_connected():
        mycursor.close()
        mydb.close()