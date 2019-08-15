package mobile.martinshare.com.martinshare.activities.MainView.PlaeneTab;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.app.ActionBar;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.MenuOptionsDesigner;
import mobile.martinshare.com.martinshare.activities.stundenplan.StundenplanActivity;
import mobile.martinshare.com.martinshare.activities.vertretungsplan.Vertretungsplan;

/**
 * Created by Modestas Valauskas on 08.02.2016.
 */
public class PlaeneTab extends Fragment implements MenuOptionsDesigner {

    ListView list;

    String[] web = {
            "Vertretungsplan",
            "Stundenplan"
    };

    Integer[] imageId = {
            R.drawable.sunclr,
            R.drawable.planclr
    };

    public PlaeneTab() {

    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.tab_plaene, container, false);

        list = (ListView) view.findViewById(R.id.listviewplaene);
        list.setAdapter(new CustomList(getActivity(), web, imageId));
        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                switch (position) {
                    case 0:
                        getActivity().startActivityForResult(new Intent(getActivity(), Vertretungsplan.class), 0);
                        break;
                    case 1:
                        getActivity().startActivityForResult(new Intent(getActivity(), StundenplanActivity.class), 0);
                        break;
                }
            }

        });

        return view;
    }

    public class CustomList extends ArrayAdapter<String>{

        private final String[] web;
        private final Integer[] imageId;

        private LayoutInflater inflater;

        public CustomList(Activity context, String[] web, Integer[] imageId) {
            super(context, R.layout.list_single, web);

            inflater = LayoutInflater.from(context);

            this.web = web;
            this.imageId = imageId;
        }

        @Override
        public View getView(int position, View view, ViewGroup parent) {

            View rowView = inflater.inflate(R.layout.list_single, parent, false);

            TextView txtTitle = (TextView) rowView.findViewById(R.id.plantitle);
            ImageView imageView = (ImageView) rowView.findViewById(R.id.planimg);

            txtTitle.setText(web[position]);
            imageView.setImageResource(imageId[position]);

            return rowView;
        }
    }

    @Override
    public void createOptionsMenu(ActionBar actionBar, Menu menu, MenuInflater menuInflater) {

        actionBar.setTitle("Pläne");
        actionBar.setSubtitle("");
        menuInflater.inflate(R.menu.menu_main, menu);
        menu.getItem(0).setIcon(R.drawable.ic_action_refresh);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    @Override
    public void optionsItemSelected(MenuItem menuItem) {

    }

}