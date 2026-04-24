import javax.swing.*;
import java.awt.*;
import java.util.ArrayList;

public class Commande_details extends JDialog {

    private JTable table;
    private MyTableModel2 model;
    private Commande commande;

    public Commande_details(Commande_liste parent, Commande commande, ArrayList<Ligne> lignes) {
        super(parent, "Détails commande " + commande.getId_commande(), true);

        this.commande = commande;

        setSize(600, 400);
        setLocationRelativeTo(parent);
        setLayout(new BorderLayout());

        if (lignes == null) {
            lignes = new ArrayList<>();
        }

        model = new MyTableModel2(lignes);
        table = new JTable(model);

        add(new JScrollPane(table), BorderLayout.CENTER);

        JLabel totalLabel = new JLabel(
                "Total TTC : " + String.format("%.2f", commande.getTotal_TTC()) + " EUR"
        );

        JPanel footer = new JPanel(new FlowLayout(FlowLayout.RIGHT));
        footer.add(totalLabel);

        JButton btnAccepter = new JButton("Accepter");
        btnAccepter.addActionListener(e -> actionCommande("accepter"));

        JButton btnRefuser = new JButton("Refuser");
        btnRefuser.addActionListener(e -> actionCommande("refuser"));

        JButton btnTerminer = new JButton("Terminer");
        btnTerminer.addActionListener(e -> actionCommande("terminer"));

        JPanel panelBoutons = new JPanel();
        panelBoutons.add(btnAccepter);
        panelBoutons.add(btnRefuser);
        panelBoutons.add(btnTerminer);

        JPanel southPanel = new JPanel(new BorderLayout());
        southPanel.add(footer, BorderLayout.NORTH);
        southPanel.add(panelBoutons, BorderLayout.SOUTH);

        add(southPanel, BorderLayout.SOUTH);
    }

    private void actionCommande(String action) {

        if (commande == null) {
            JOptionPane.showMessageDialog(this, "Commande introuvable.");
            return;
        }

        String statut = commande.getStatut(); // ⚠️ il faut un champ statut dans Commande
        int idCommande = commande.getId_commande();

        try {

            // ACCEPTATION
            if ("accepter".equals(action)) {

                if (!"EN_ATTENTE".equals(statut)) {
                    JOptionPane.showMessageDialog(this,
                            "Impossible : seule une commande en attente peut être acceptée.");
                    return;
                }

                NetworkUtils.accepterCommande(idCommande);
                commande.setStatut("ACCEPTEE");
            }

            // REFUS
            else if ("refuser".equals(action)) {

                if (!"EN_ATTENTE".equals(statut)) {
                    JOptionPane.showMessageDialog(this,
                            "Impossible : seule une commande en attente peut être refusée.");
                    return;
                }

                NetworkUtils.refuserCommande(idCommande);
                commande.setStatut("REFUSEE");
            }

            // TERMINER
            else if ("terminer".equals(action)) {

                if (!"ACCEPTEE".equals(statut)) {
                    JOptionPane.showMessageDialog(this,
                            "Impossible : seule une commande acceptée peut être terminée.");
                    return;
                }

                NetworkUtils.terminerCommande(idCommande);
                commande.setStatut("TERMINEE");
            }

            JOptionPane.showMessageDialog(this, "Action effectuée !");
            dispose();

        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }
}
