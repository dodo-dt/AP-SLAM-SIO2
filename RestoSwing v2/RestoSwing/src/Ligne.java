public class Ligne {

    private int id_commande;
    private int id_produit;
    private String produit;
    private int quantite;
    private double montant_unitaire_ht;

    public Ligne(int id_commande, int id_produit, String produit, int quantite, double montant_unitaire_ht) {
        this.id_commande = id_commande;
        this.id_produit = id_produit;
        this.produit = produit;
        this.quantite = quantite;
        this.montant_unitaire_ht = montant_unitaire_ht;
    }

    public Ligne() {

    }

    // Getter

    public int getId_commande() {

        return id_commande;
    }

    public int getId_produit() {

        return id_produit;
    }

    public String getProduit() {
        return produit;
    }

    public int getQuantite() {

        return quantite;
    }

    public double getMontant_unitaire_ht() {
        return montant_unitaire_ht;
    }

    // Setter

    public void setId_commande(int id_commande) {
        this.id_commande = id_commande;
    }

    public void setId_produit(int id_produit) {
        this.id_produit = id_produit;
    }

    public void setProduit(String produit) {
        this.produit = produit;
    }

    public void setQuantite(int quantite) {
        this.quantite = quantite;
    }

    public void setMontant_unitaire_ht(double montant_unitaire_ht) {
        this.montant_unitaire_ht = montant_unitaire_ht;
    }
}