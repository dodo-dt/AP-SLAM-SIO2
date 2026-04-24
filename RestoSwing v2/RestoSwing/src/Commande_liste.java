import javax.swing.*;
import java.awt.*;
import java.util.ArrayList;

public class Commande_liste extends JFrame {

    private JTable table;
    private MyTableModel model;
    private ArrayList<Commande> commandes = new ArrayList<>();

    public Commande_liste() {
        setTitle("Commandes en attente");
        setSize(980, 500);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // Table
        model = new MyTableModel(commandes);
        table = new JTable(model);

        JScrollPane scrollPane = new JScrollPane(table);
        add(scrollPane, BorderLayout.CENTER);

        // Boutons
        JButton btnRefresh = new JButton("Rafraîchir");
        btnRefresh.addActionListener(e -> chargerCommande());

        JButton btnDetails = new JButton("Voir détails");
        btnDetails.addActionListener(e -> voirDetails());

        JPanel panel = new JPanel();
        panel.add(btnRefresh);
        panel.add(btnDetails);

        add(panel, BorderLayout.SOUTH);

        // Chargement initial
        chargerCommande();
    }

    private void voirDetails() {
        Commande commande = getCommandeSelectionnee();

        if (commande == null) {
            JOptionPane.showMessageDialog(this, "Sélectionnez une commande.", "Information", JOptionPane.INFORMATION_MESSAGE);
            return;
        }

        try {
            ArrayList<Ligne> lignes = (ArrayList<Ligne>) NetworkUtils.getDetailsCommande(commande.getId_commande());
            commande.setLignes(lignes);

            new Commande_details(this, commande, lignes).setVisible(true);

        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }

    private void chargerCommande() {
        try {
            commandes = (ArrayList<Commande>) NetworkUtils.getCommandesEnAttente();
            model.setCommandes(commandes);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }

    private Commande getCommandeSelectionnee() {
        int row = table.getSelectedRow();

        if (commandes == null || row < 0 || row >= commandes.size()) {
            return null;
        }

        return commandes.get(row);
    }
}