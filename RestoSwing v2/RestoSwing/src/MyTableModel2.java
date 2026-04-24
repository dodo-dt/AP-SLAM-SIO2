import javax.swing.table.AbstractTableModel;
import java.lang.reflect.Array;
import java.util.ArrayList;
import java.util.List;

public class MyTableModel2 extends AbstractTableModel {

    private static final String[] cols = {
            "ID Produit", "Produit", "Quantite", "Montant HT"
    };

    private ArrayList<Ligne> lignes = new ArrayList<>();

    public MyTableModel2(ArrayList<Ligne> lignes) {
        this.lignes = lignes;
    }

    public int getRowCount() {
        return lignes.size();
    }

    public int getColumnCount() {
        return cols.length;
    }

    public Object getValueAt(int rowIndex, int columnIndex) {

        Ligne l = lignes.get(rowIndex);

        switch (columnIndex) {
            case 0:
                return l.getId_produit();
            case 1:
                return l.getProduit();
            case 2:
                return l.getQuantite();
            case 3:
                return l.getMontant_unitaire_ht();
            default: return null;
        }
    }

    public String getColumnName(int column) {
        return cols[column];
    }
}