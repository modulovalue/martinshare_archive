package mobile.martinshare.com.martinshare.activities.vertretungsplan;

import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.widget.ImageView;

import com.koushikdutta.ion.Ion;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.R;

/**
 * Created by Modestas Valauskas on 11.04.2015.
 */
public class PlanTab extends Fragment {

    WebView webView;

    ImageView imageViewVertretungsplan;

    private String url;
    SweetAlertDialog pDialog;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, final ViewGroup container, Bundle savedInstanceState) {

        url = getArguments().getString("url");
        if(urlIsPicture(url)) {
            return inflater.inflate(R.layout.vertretungsplan_tab_image, container, false);
        } else {
            return inflater.inflate(R.layout.vertretungsplan_tab, container, false);
        }
    }

    @Override
    public void onResume() {
        super.onResume();
        addPlan();
    }



    public void addPlan() {


        pDialog = new SweetAlertDialog(getActivity(), SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        pDialog.setTitleText("Seite wird geladen");
        pDialog.setContentText("Bitte warten").setCancelable(false);

        pDialog.show();


        final String  sURL = getArguments().getString("url");

        if(urlIsPicture(url)) {

            imageViewVertretungsplan = (ImageView) getView().findViewById(R.id.imageViewVertretungsplan);

            Ion.getDefault(getContext()).getBitmapCache().clear();
            Ion.with(getContext())
                    .load(getArguments().getString("url"))
                    .noCache()
                    .withBitmap()
                    .placeholder(R.drawable.eslaedt)
                    .intoImageView(imageViewVertretungsplan);

            getActivity().runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    pDialog.hide();
                }
            });

        } else {

            webView = (WebView) getView().findViewById(R.id.webView);
            webView.clearCache(true);

            webView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);
            webView.getSettings().setUseWideViewPort(true);
            webView.getSettings().setLoadWithOverviewMode(true);
            webView.getSettings().setBuiltInZoomControls(true);

            Thread thread = new Thread(new Runnable() {
                @Override
                public void run() {
                    try {

                        URL url = new URL(sURL);
                        HttpURLConnection httpCon = (HttpURLConnection) url.openConnection();
                        httpCon.addRequestProperty("Connection", "keep-alive");
                        httpCon.addRequestProperty("Cache-Control", "max-age=0");
                        httpCon.addRequestProperty("User-Agent", "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36");
                        HttpURLConnection.setFollowRedirects(false);
                        httpCon.setInstanceFollowRedirects(false);
                        httpCon.setDoOutput(true);
                        httpCon.setUseCaches(false);

                        httpCon.setRequestMethod("GET");

                        BufferedReader in = new BufferedReader(new InputStreamReader(httpCon.getInputStream(), "UTF-8"));
                        String inputLine;
                        StringBuilder a = new StringBuilder();
                        while ((inputLine = in.readLine()) != null)
                            a.append(inputLine);
                        in.close();

                        httpCon.disconnect();


                        loadWebview(prepateHtmlCode(a.toString()));


                        getActivity().runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                pDialog.hide();
                            }
                        });

                    } catch (Exception e) {
                        getActivity().runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                pDialog.hide();
                            }
                        });
                    }
                }
            });

            thread.start();

        }



    }

    public boolean urlIsPicture(String url) {
        String extension = url.substring(url.lastIndexOf("."));
        if(extension.equals(".png") ||
                extension.equals(".jpg") ||
                extension.equals(".PNG") ||
                extension.equals(".JPG") ||
                extension.equals(".JPEG") ||
                extension.equals(".jpeg")) {
            return true;
        } else {
            return false;
        }
    }

    public void loadWebview(final String htmlCode) {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                webView.getSettings().setJavaScriptEnabled(false);
                webView.loadDataWithBaseURL("", htmlCode, "text/html", "UTF-8", "");
                pDialog.hide();
            }
        });
    }

    public String prepateHtmlCode(String htmlCode) {

        String pre= "<span style=\"color: #f30000; font-size: "+ Prefs.getVertretungsplanMarkierungSize(getActivity())+"pt; \">";
        String post= "</span>";

        String bedingung = Prefs.getVertretungsplanMarkierungText(getActivity());

        String[] split = htmlCode.split(bedingung);

        String result = "";

        int looplength = -1+split.length;

        if(split.length != 0) {
            for( int i = 0; i< looplength; i++) {
                result += split[i] + pre + bedingung + post;
            }
            result += split[split.length-1];
        } else {
            result = htmlCode;
        }

        return result;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if(pDialog != null) {
            pDialog.dismiss();
        }
    }
}
