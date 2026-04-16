import java.util.ArrayList;
import java.util.List;

public class Commande {
    private int id;
    private String libelle;
    private String typeCommande;
    private String dateCommande;
    private double totalTtc;
    private int idEtat;
    private String statut;
    private List<Ligne> lignes = new ArrayList<>();

    public Commande() {}

    public Commande(int id, String libelle, String typeCommande, String dateCommande, double totalTtc, int idEtat, String statut) {
        this.id = id;
        this.libelle = libelle;
        this.typeCommande = typeCommande;
        this.dateCommande = dateCommande;
        this.totalTtc = totalTtc;
        this.idEtat = idEtat;
        this.statut = statut;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getLibelle() { return libelle; }
    public void setLibelle(String libelle) { this.libelle = libelle; }

    public String getTypeCommande() { return typeCommande; }
    public void setTypeCommande(String typeCommande) { this.typeCommande = typeCommande; }

    public String getDateCommande() { return dateCommande; }
    public void setDateCommande(String dateCommande) { this.dateCommande = dateCommande; }

    public double getTotalTtc() { return totalTtc; }
    public void setTotalTtc(double totalTtc) { this.totalTtc = totalTtc; }

    public int getIdEtat() { return idEtat; }
    public void setIdEtat(int idEtat) { this.idEtat = idEtat; }

    public String getStatut() { return statut; }
    public void setStatut(String statut) { this.statut = statut; }

    public List<Ligne> getLignes() { return lignes; }
    public void setLignes(List<Ligne> lignes) { this.lignes = lignes; }

    public void addLigne(Ligne l) {
        this.lignes.add(l);
    }
}