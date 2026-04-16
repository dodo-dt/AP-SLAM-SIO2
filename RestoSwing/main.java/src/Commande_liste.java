import javax.swing.*;
import java.awt.*;
import java.util.ArrayList;

public class Commande_liste extends JFrame {

    private JTable table;
    private MyTableModel model;
    private java.util.ArrayList<Commande> commandes = new ArrayList<>();
    private final APIService apiService = new APIService();

    public Commande_liste() {
        setTitle("Commandes en attente");
        setSize(980, 500);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        model = new MyTableModel(commandes);
        table = new JTable(model);
        table.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);

        JScrollPane scroll = new JScrollPane(table);

        JButton btnRefresh = new JButton("Rafraichir");
        btnRefresh.addActionListener(e -> chargerCommandes());

        JButton btnDetails = new JButton("Voir details");
        btnDetails.addActionListener(e -> openDetails());

        JButton btnAccepter = new JButton("Accepter");
        btnAccepter.addActionListener(e -> appliquerActionCommande("accepter"));

        JButton btnRefuser = new JButton("Refuser");
        btnRefuser.addActionListener(e -> appliquerActionCommande("refuser"));

        JButton btnTerminer = new JButton("Terminer");
        btnTerminer.addActionListener(e -> appliquerActionCommande("terminer"));

        JPanel actions = new JPanel(new FlowLayout(FlowLayout.LEFT));
        actions.add(btnRefresh);
        actions.add(btnDetails);
        actions.add(btnAccepter);
        actions.add(btnRefuser);
        actions.add(btnTerminer);

        add(scroll, BorderLayout.CENTER);
        add(actions, BorderLayout.SOUTH);

        chargerCommandes();
    }

    private void openDetails() {
        Commande commande = getCommandeSelectionnee();
        if (commande == null) {
            JOptionPane.showMessageDialog(this, "Selectionnez une commande.", "Information", JOptionPane.INFORMATION_MESSAGE);
            return;
        }

        try {
            java.util.List<Ligne> lignes = apiService.getDetailsCommande(commande.getId());
            commande.setLignes(lignes);
            new Commande_details(this, commande, lignes).setVisible(true);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }

    private void appliquerActionCommande(String action) {
        Commande commande = getCommandeSelectionnee();
        if (commande == null) {
            JOptionPane.showMessageDialog(this, "Selectionnez une commande.", "Information", JOptionPane.INFORMATION_MESSAGE);
            return;
        }

        try {
            String message = executerAction(action, commande.getId());

            JOptionPane.showMessageDialog(this, message, "Succes", JOptionPane.INFORMATION_MESSAGE);
            chargerCommandes();
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }

    private Commande getCommandeSelectionnee() {
        int row = table.getSelectedRow();
        if (row < 0 || row >= commandes.size()) {
            return null;
        }
        return commandes.get(row);
    }

    private String executerAction(String action, int idCommande) {
        if ("accepter".equals(action)) {
            return apiService.accepterCommande(idCommande);
        }
        if ("refuser".equals(action)) {
            return apiService.refuserCommande(idCommande);
        }
        return apiService.terminerCommande(idCommande);
    }

    private void chargerCommandes() {
        try {
            commandes = (ArrayList<Commande>) apiService.getCommandesEnAttente();
            model = new MyTableModel(commandes);
            table.setModel(model);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }
}