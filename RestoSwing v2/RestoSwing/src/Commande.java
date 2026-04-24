import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class Commande {

    private int id_commande;
    private String lib_commande;
    private String type_commande;
    private double total_TTC;
    private String date_commande;
    private int id_etat;
    private String statut;
    private ArrayList<Ligne> lignes = new ArrayList<>();


    public Commande(int id_commande, String lib_commande, String type_commande, double total_TTC, String date_commande, int id_etat, String statut) {
        this.id_commande = id_commande;
        this.lib_commande = lib_commande;
        this.type_commande = type_commande;
        this.total_TTC = total_TTC;
        this.date_commande = date_commande;
        this.id_etat = id_etat;
        this.statut = statut;

    }

    public Commande() {

    }

    // Getter

    public int getId_commande() {
        return id_commande;
    }

    public String getLib_commande() {
        return lib_commande;
    }

    public String getType_commande() {
        return type_commande;
    }

    public String getDate_commande() {
        return date_commande;
    }

    public double getTotal_TTC() {
        return total_TTC;
    }

    public int getId_etat() {
        return id_etat;
    }

    public String getStatut() {
        return statut;
    }

    // Setter

    public void setId_commande(int id_commande) {
        this.id_commande = id_commande;
    }

    public void setLib_commande(String lib_commande) {
        this.lib_commande = lib_commande;
    }

    public void setType_commande(String type_commande) {
        this.type_commande = type_commande;
    }

    public void setDate_commande(String date_commande) {
        this.date_commande = date_commande;
    }

    public void setTotal_TTC(double total_TTC) {
        this.total_TTC = total_TTC;
    }

    public void setId_etat(int id_etat) {
        this.id_etat = id_etat;
    }

    public void setStatut(String statut) {
        this.statut = statut;
    }

    // ArrayList de LigneCommande

    public ArrayList<Ligne> getLignes() { return lignes; }
    public void setLignes(ArrayList<Ligne> lignes) { this.lignes = lignes; }

    public void addLigne(Ligne l) {
        this.lignes.add(l);
    }
}
