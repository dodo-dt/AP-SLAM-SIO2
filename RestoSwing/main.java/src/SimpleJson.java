import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

public class SimpleJson {

    private final String text;
    private int index;

    private SimpleJson(String text) {
        this.text = text;
        this.index = 0;
    }

    public static Object parse(String json) {
        SimpleJson parser = new SimpleJson(json);
        Object value = parser.parseValue();
        parser.skipWhitespace();
        if (!parser.isEnd()) {
            throw new IllegalArgumentException("JSON invalide: caractere inattendu a la position " + parser.index);
        }
        return value;
    }

    private Object parseValue() {
        skipWhitespace();
        if (isEnd()) {
            throw new IllegalArgumentException("JSON invalide: fin inattendue");
        }

        char c = current();
        if (c == '{') {
            return parseObject();
        }
        if (c == '[') {
            return parseArray();
        }
        if (c == '"') {
            return parseString();
        }
        if (c == 't') {
            consumeLiteral("true");
            return Boolean.TRUE;
        }
        if (c == 'f') {
            consumeLiteral("false");
            return Boolean.FALSE;
        }
        if (c == 'n') {
            consumeLiteral("null");
            return null;
        }
        if (c == '-' || Character.isDigit(c)) {
            return parseNumber();
        }

        throw new IllegalArgumentException("JSON invalide: valeur inattendue a la position " + index);
    }

    private Map<String, Object> parseObject() {
        Map<String, Object> obj = new LinkedHashMap<>();
        expect('{');
        skipWhitespace();

        if (!isEnd() && current() == '}') {
            index++;
            return obj;
        }

        while (true) {
            skipWhitespace();
            String key = parseString();
            skipWhitespace();
            expect(':');
            Object value = parseValue();
            obj.put(key, value);
            skipWhitespace();

            if (!isEnd() && current() == ',') {
                index++;
                continue;
            }
            if (!isEnd() && current() == '}') {
                index++;
                break;
            }
            throw new IllegalArgumentException("JSON invalide: '}' attendu a la position " + index);
        }

        return obj;
    }

    private List<Object> parseArray() {
        List<Object> arr = new ArrayList<>();
        expect('[');
        skipWhitespace();

        if (!isEnd() && current() == ']') {
            index++;
            return arr;
        }

        while (true) {
            arr.add(parseValue());
            skipWhitespace();

            if (!isEnd() && current() == ',') {
                index++;
                continue;
            }
            if (!isEnd() && current() == ']') {
                index++;
                break;
            }
            throw new IllegalArgumentException("JSON invalide: ']' attendu a la position " + index);
        }

        return arr;
    }

    private String parseString() {
        expect('"');
        StringBuilder sb = new StringBuilder();

        while (!isEnd()) {
            char caractere = current();
            index++;

            if (caractere == '"') {
                return sb.toString();
            }
            if (caractere == '\\') {
                if (isEnd()) {
                    throw new IllegalArgumentException("JSON invalide: sequence d'echappement incomplete");
                }
                char echap = current();
                index++;
                switch (echap) {
                    case '"': sb.append('"'); break;
                    case '\\': sb.append('\\'); break;
                    case '/': sb.append('/'); break;
                    case 'b': sb.append('\b'); break;
                    case 'f': sb.append('\f'); break;
                    case 'n': sb.append('\n'); break;
                    case 'r': sb.append('\r'); break;
                    case 't': sb.append('\t'); break;
                    case 'u':
                        if (index + 4 > text.length()) {
                            throw new IllegalArgumentException("JSON invalide: unicode incomplet");
                        }
                        String hex = text.substring(index, index + 4);
                        index += 4;
                        try {
                            sb.append((char) Integer.parseInt(hex, 16));
                        } catch (NumberFormatException ex) {
                            throw new IllegalArgumentException("JSON invalide: unicode invalide " + hex);
                        }
                        break;
                    default:
                        throw new IllegalArgumentException("JSON invalide: echappement '\\" + echap + "' non supporte");
                }
                continue;
            }

            sb.append(caractere);
        }

        throw new IllegalArgumentException("JSON invalide: chaine non fermee");
    }

    private Number parseNumber() {
        int start = index;

        if (current() == '-') {
            index++;
        }
        consumeDigits();

        if (!isEnd() && current() == '.') {
            index++;
            consumeDigits();
        }

        if (!isEnd() && (current() == 'e' || current() == 'E')) {
            index++;
            if (!isEnd() && (current() == '+' || current() == '-')) {
                index++;
            }
            consumeDigits();
        }

        String number = text.substring(start, index);
        try {
            if (number.contains(".") || number.contains("e") || number.contains("E")) {
                return Double.parseDouble(number);
            }
            long value = Long.parseLong(number);
            if (value >= Integer.MIN_VALUE && value <= Integer.MAX_VALUE) {
                return (int) value;
            }
            return value;
        } catch (NumberFormatException ex) {
            throw new IllegalArgumentException("JSON invalide: nombre invalide " + number);
        }
    }

    private void consumeDigits() {
        if (isEnd() || !Character.isDigit(current())) {
            throw new IllegalArgumentException("JSON invalide: chiffre attendu a la position " + index);
        }
        while (!isEnd() && Character.isDigit(current())) {
            index++;
        }
    }

    private void consumeLiteral(String literal) {
        if (index + literal.length() > text.length() || !text.substring(index, index + literal.length()).equals(literal)) {
            throw new IllegalArgumentException("JSON invalide: literal attendu " + literal + " a la position " + index);
        }
        index += literal.length();
    }

    private void expect(char c) {
        skipWhitespace();
        if (isEnd() || current() != c) {
            throw new IllegalArgumentException("JSON invalide: '" + c + "' attendu a la position " + index);
        }
        index++;
    }

    private void skipWhitespace() {
        while (!isEnd()) {
            char ch = current();
            if (ch == ' ' || ch == '\n' || ch == '\r' || ch == '\t') {
                index++;
                continue;
            }
            break;
        }
    }

    private char current() {
        return text.charAt(index);
    }

    private boolean isEnd() {
        return index >= text.length();
    }
}
