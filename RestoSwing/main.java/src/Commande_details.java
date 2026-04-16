import javax.swing.*;
import java.awt.*;
import java.util.ArrayList;
import java.util.List;

public class Commande_details extends JDialog {

    private JTable table;
    private MyTableModel2 model;
    private JLabel totalLabel;

    public Commande_details(JFrame parent, Commande commande, List<Ligne> lignes) {
        super(parent, "Détails commande " + commande.getId(), true);
        setSize(700, 380);
        setLocationRelativeTo(parent);
        setLayout(new BorderLayout());

        List<Ligne> safeLines = lignes == null ? new ArrayList<>() : lignes;
        model = new MyTableModel2(safeLines);
        table = new JTable(model);

        totalLabel = new JLabel("Total TTC commande : " + String.format("%.2f", commande.getTotalTtc()) + " EUR");
        JPanel footer = new JPanel(new FlowLayout(FlowLayout.RIGHT));
        footer.add(totalLabel);

        add(new JScrollPane(table), BorderLayout.CENTER);
        add(footer, BorderLayout.SOUTH);
    }
}