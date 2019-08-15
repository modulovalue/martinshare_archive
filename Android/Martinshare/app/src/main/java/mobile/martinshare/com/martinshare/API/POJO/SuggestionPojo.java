package mobile.martinshare.com.martinshare.API.POJO;

import java.util.ArrayList;

public class SuggestionPojo extends ArrayList<SuggestionsContainer> {

   public String getName(int index) {
      return super.get(index).name;
   }
}

class SuggestionsContainer {
    public String name;

    public String getName() {
        return this.name;
    }
}
