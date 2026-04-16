public class Ligne {
    private int idCommande;
    private int idProduit;
    private String produit;
    private int quantite;
    private double montantUnitaireHt;

    public Ligne() {}

    public Ligne(int idCommande, int idProduit, String produit, int quantite, double montantUnitaireHt) {
        this.idCommande = idCommande;
        this.idProduit = idProduit;
        this.produit = produit;
        this.quantite = quantite;
        this.montantUnitaireHt = montantUnitaireHt;
    }

    public int getIdCommande() { return idCommande; }
    public void setIdCommande(int idCommande) { this.idCommande = idCommande; }

    public int getIdProduit() { return idProduit; }
    public void setIdProduit(int idProduit) { this.idProduit = idProduit; }

    public String getPlat() { return produit; }
    public void setPlat(String plat) { this.produit = plat; }

    public int getQuantite() { return quantite; }
    public void setQuantite(int quantite) { this.quantite = quantite; }

    public double getPrix() { return montantUnitaireHt; }
    public void setPrix(double prix) { this.montantUnitaireHt = prix; }
}