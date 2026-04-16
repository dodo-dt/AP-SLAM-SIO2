import javax.swing.table.AbstractTableModel;
import java.util.List;

public class MyTableModel2 extends AbstractTableModel {

    private static final String[] COLONNES = {"ID Produit", "Plat", "Quantite", "Montant HT"};
    private final List<Ligne> lignes;

    public MyTableModel2(List<Ligne> lignes) {
        this.lignes = lignes;
    }


    public int getRowCount() {
        return lignes.size();
    }


    public int getColumnCount() {
        return COLONNES.length;
    }


    public Object getValueAt(int rowIndex, int columnIndex) {
        Ligne l = lignes.get(rowIndex);

        switch (columnIndex) {
            case 0: return l.getIdProduit();
            case 1: return l.getPlat();
            case 2: return l.getQuantite();
            case 3: return l.getPrix();
            default: return null;
        }
    }


    public String getColumnName(int column) {
        return COLONNES[column];
    }
}