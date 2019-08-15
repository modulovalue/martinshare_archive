package mobile.martinshare.com.martinshare.API.POJO;

import org.joda.time.DateTime;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;

import mobile.martinshare.com.martinshare.activities.EintragObj;

/**
 * Created by Modestas Valauskas on 12.04.2015.
 */
public class EintraegeList extends ArrayList<EintragObj> implements Serializable {

    public static String fileName = "martineintraege.share";

    public EintraegeList() {

    }

}

