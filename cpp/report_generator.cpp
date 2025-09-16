// cpp/report_generator.cpp
#include <bits/stdc++.h>
using namespace std;
int main(int argc, char** argv) {
string period = "daily";
if (argc > 1) period = argv[1]; // daily, weekly, monthly
ifstream ifs("data/transactions.csv");
if (!ifs) { cerr<<"Cannot open data/transactions.csv\n"; return 1; }
string line;
vector<vector<string>> rows;
// read header
if (!getline(ifs, line)) return 1;
while (getline(ifs, line)) {
stringstream ss(line);
vector<string> cols;
string cell;
while (getline(ss, cell, ',')) cols.push_back(cell);
if (cols.size() >= 4) rows.push_back(cols);
}
map<string, pair<int,double>> groups; // key -> (count,total)
for (auto &r : rows) {
string order_ref = r[0];
double total = 0.0;
try { total = stod(r[1]); } catch(...){}
string status = r[2];
string created = r[3];
// parse date portion
string date = created.substr(0,10); // YYYY-MM-DD
string key;
if (period=="daily") key = date;
else if (period=="monthly") key = date.substr(0,7); // YYYY-MM
else { // weekly: get iso week using simple algorithm: fallback to
date's YYYY-W##
// naive: use YYYY-W + week number from tm
struct tm tm{};
strptime(date.c_str(), "%Y-%m-%d", &tm);
char buf[32];
strftime(buf, sizeof(buf), "%Y-W%V", &tm);
key = string(buf);
}
groups[key].first += 1;
groups[key].second += total;
}
string out = string("data/report_") + period + ".csv";
ofstream ofs(out);
ofs << "period,orders,total\n";
for (auto &kv : groups) {
ofs << kv.first << "," << kv.second.first << "," << kv.second.second <<
"\n";
}
cout << "Report written to " << out << "\n";
return 0;
}
