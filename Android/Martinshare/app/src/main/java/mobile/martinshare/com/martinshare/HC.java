package mobile.martinshare.com.martinshare;

import android.content.Context;
import android.graphics.ColorFilter;
import android.graphics.ColorMatrixColorFilter;
import android.graphics.drawable.Drawable;
import android.view.View;
import android.widget.ImageView;

import org.joda.time.DateTime;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;

/**
 * Created by Modestas Valauskas on 30.12.2014.
 */
public class HC {

    public static String addlead0(int number) {
        if (number <= 9) {
            String nul = "0";
            nul = nul + String.valueOf(number);
            return nul;
        } else {
            return String.valueOf(number);
        }
    }

    public static Drawable InvertColor(Context c, int drawablei) {

        float[] colorMatrix_Negative = {
                0, 0, 0, 0, 255, //red
                0, 0, 0, 0, 255, //green
                0, 0, 0, 0, 255, //blue
                0, 0, 0, 1.0f, 0 //alpha
        };
        ColorFilter colorFilter_Negative = new ColorMatrixColorFilter(colorMatrix_Negative);
        Drawable drawable = c.getResources().getDrawable(drawablei);
        drawable.setColorFilter(colorFilter_Negative);
        return drawable;
    }

}

