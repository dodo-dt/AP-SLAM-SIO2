import javax.swing.table.AbstractTableModel;
import java.util.ArrayList;
import java.util.List;

public class MyTableModel extends AbstractTableModel {

    private static final String[] cols = {"ID", "Libelle", "Type", "Date", "Total TTC", "Statut"};
    private final ArrayList<Commande> commandes;

    public MyTableModel(ArrayList<Commande> commandes) {
        this.commandes = commandes;
    }

    public int getRowCount() {
        return commandes.size();
    }

    public int getColumnCount() {
        return cols.length;
    }

    public Object getValueAt(int rowIndex, int columnIndex) {
        Commande c = commandes.get(rowIndex);

        for(String col : cols) {
            c.getId();
            c.getLibelle();
            c.getTypeCommande();
            c.getDateCommande();
            c.getTotalTtc();
            c.getStatut();
        }
    }

    public String getColumnName(int column) {
        return cols[column];
    }
}