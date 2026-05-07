import javax.swing.table.AbstractTableModel;
import java.util.ArrayList;

public class MyTableModel extends AbstractTableModel {

    private static final String[] cols = {
            "ID", "Type", "Date", "nb plat", "Total TTC", "Statut"
    };

    private ArrayList<Commande> commandes;

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

        switch (columnIndex) {
            case 0: return c.getId_commande();
            case 1: return c.getType_commande();
            case 2: return c.getDate_commande();
            case 3: return c.getNombrePlats();
            case 4: return c.getTotal_TTC();
            case 5: return c.getStatut();
            default: return null;
        }
    }

    public String getColumnName(int column) {
        return cols[column];
    }

    public void setCommandes(ArrayList<Commande> commandes) {
        this.commandes = commandes;
        fireTableDataChanged();
    }
}